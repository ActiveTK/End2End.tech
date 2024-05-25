/*!
 * index.js - End2End.tech
 */

"use strict";
(function (window, undefined) {

    document.addEventListener("DOMContentLoaded", function () {

        _("main").style.display = "block";

        _("setLimitDownload").onchange = function () {
            _("setLimitDownloadInput").classList.toggle("default-remove");
        }
        _("setPassword").onchange = function () {
            _("setPasswordInput").classList.toggle("default-remove");
        }
        _("setDateLimit").onchange = function () {
            _("setDateLimitInput").classList.toggle("default-remove");
        }

        const pswmeter = passwordStrengthMeter({
            containerElement: '#pswmeter',
            passwordInput: '#password',
            showMessage: true,
            messageContainer: '#pswmetermsg',
            borderRadius: 2
        });

        var password = "", based = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$%()=-^¥[@]:;{}_/.";
        for (var i = 0; i < 12; i++)
            password += based.charAt(Math.floor(Math.random() * based.length));
        _("password").value = password;

        _("password").onchange = function () {
            if (_("password").value) {
                _("pswmeter").style.display = "block";
                _("pswmetermsg").style.display = "block";
            }
        }

        _("uploadzone").addEventListener('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var files = e.dataTransfer.files;
            _("file").files = files;
        }, false);
        _("uploadzone").addEventListener('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
        }, false);
        _("uploadzone").addEventListener('dragleave', function (e) {
            e.stopPropagation();
        }, false);

        _("uploader").onsubmit = function () {

            if (_("file").files.length == 0)
                return !alert("ファイルを選択して下さい。");

            _("submitData").disabled = true;
            _("stat").innerText = "ファイルをアップロードしています..。";

            _("password").removeAttribute("name");

            if (!_("setLimitDownload").checked)
                _("maxDownloadCount").removeAttribute("name");

            if (!_("setDateLimit").checked)
                _("DownloadLimit").removeAttribute("name");

            if (_("setPassword").checked) {

                if (!_("password").value)
                    return !alert("パスワードを指定して下さい。");

                if (_("file").files.length != 1)
                    return !alert("複数のファイルを同時に暗号化してアップロードすることはできません。");

                if (_("file").files[0].size > 1024 * 1024 * 100) {
                    return !alert("100MBを超える巨大なファイルの暗号化には対応していません。");
                }

                let reader = new FileReader();
                reader.readAsDataURL(_("file").files[0]);
                reader.onload = () => {
                    _("stat").innerText = "データを暗号化しています..。";
                    var salt = CryptoJS.lib.WordArray.random(128 / 8);
                    var iv = CryptoJS.lib.WordArray.random(128 / 8);
                    var enc = CryptoJS.enc.Hex.stringify(salt) + ',' + CryptoJS.enc.Hex.stringify(iv) + ',' +
                        CryptoJS.AES.encrypt(
                            CryptoJS.enc.Utf8.parse(reader.result),
                            CryptoJS.PBKDF2(
                                CryptoJS.enc.Utf8.parse(_("password").value),
                                salt,
                                {
                                    keySize: 128 / 8,
                                    iterations: 500
                                }
                            ),
                            {
                                iv: iv,
                                mode: CryptoJS.mode.CBC,
                                padding: CryptoJS.pad.Pkcs7
                            }
                        );

                    let list = new DataTransfer();
                    let file = new File([enc], _("file").files[0].name);
                    list.items.add(file);
                    _("fileOne").files = list.files;

                    enc = "";

                    sendFile(new FormData($("#uploader").get(0)));

                    try {
                        _("file").value = "";
                    } catch { }


                };
            }
            else {

                const fList = _("file").files;

                for (let i = 0; i < fList.length; i++) {

                    let list = new DataTransfer();
                    list.items.add(fList[i]);
                    _("fileOne").files = list.files;

                    if (fList[i].size > 1024 * 1024 * 100) {
                        sendBigFile(_("fileOne").files[0]);
                    } else {
                        sendFile(new FormData($("#uploader").get(0)));
                    }

                }

                try {

                    _("file").value = "";
                    _("fileOne").value = "";

                    _("submitData").disabled = false;
                    _("password").setAttribute("name", "passwd");
                    if (!_("setLimitDownload").checked)
                        _("maxDownloadCount").setAttribute("name", "maxDownloadCount");
                    if (!_("setDateLimit").checked)
                        _("DownloadLimit").setAttribute("name", "DownloadLimit");

                } catch { }

            }

            return false;
        }

    });

    function sendFile(Data) {

        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable)
                        _("stat").innerText = "送信中..(" + (evt.loaded / evt.total * 100).toFixed(2) + "%完了)";
                }, false);
                return xhr;
            },
            url: window.end2endtech.Endpoint + "upload",
            type: "POST",
            data: Data,
            cache: !1,
            contentType: !1,
            processData: !1,
            dataType: "json"

        }).done(function (t) {

            for (let b in t) {
                if (b == "Error") {
                    _("stat").innerText = t["Error"];
                }
                else if (b == "Status" && t["Status"] == "OK") {
                    createNewURL(t);
                }
            }

        }).fail(function (t, e, o) {

            _("stat").innerText = "送信中にエラーが発生しました:" + o;

        });

    }

    function sendBigFile(filedata) {

        let pieceSize = 80 * 1024 * 1024;
        let pieceCount = Math.ceil(filedata.size / pieceSize);
        getOverridableKey(filedata, function(dataKey) {
            
            if (dataKey["Error"]) {
                _("stat").innerText = dataKey["Error"];
                return;
            }

            UploadPeaceWithLoop(dataKey, filedata, 0, pieceCount, pieceSize, Date.now(), function() {

                _("stat").innerText = "";

                try {
                    _("fileOne").value = "";
                } catch (e) { }
    
                createNewURL(dataKey);

            });

        });

    }

    function UploadPeaceWithLoop(dataKey, filedata, sentFileCount, pieceCount, pieceSize, startDate, finalFunction) {

        if (sentFileCount == 0) {
            let speed_guess = "計測中";
            let time_guess = "計測中";
        } else {
            let speed_guess = (pieceSize * sentFileCount / ((Date.now() - startTime) / 1000) / 1024 / 1024).toFixed(2);
            let time_guess = pieceSize * (pieceCount - sentFileCount) / speed_guess;
        }

        _("stat").innerHTML = "チャンク送信 " + Math.ceil(100 * sentFileCount / pieceCount).toString() + "%完了(" + sentFileCount + "/" + pieceCount + ")<br>" +
                              "通信速度: " + speed_guess + "Mbps, 推定残り時間: " + time_guess + "秒";

        let pieceOfFile = new FormData;
        pieceOfFile.append("RawData", filedata.slice(sentFileCount * pieceSize, (sentFileCount + 1) * pieceSize));
        pieceOfFile.append("FileID", dataKey["FileID"]);

        __sendPieceOfFile(pieceOfFile).then(resultArray => {

            if (resultArray["Error"]) {
                if (confirm("巨大ファイルの分割送信時にエラーが発生しました。\n" + resultArray["Error"] + "\n再送を行いますか？"))
                    sentFileCount -= 1;
                else
                    return;
            }

            sentFileCount++;
            if ( sentFileCount != pieceCount )
                UploadPeaceWithLoop(dataKey, filedata, sentFileCount, pieceCount, pieceSize, startDate, finalFunction);
            else
                finalFunction();

        });
    }

    function getOverridableKey(fileData, callback) {

        let request_key = new FormData;
        request_key.append("filename", fileData.name);
        request_key.append("size", fileData.size);

        if (_("blockVPN").checked)
            request_key.append("blockVPN", "true");
        if (_("setLimitDownload").checked) {
            request_key.append("setLimitDownload", "true");
            request_key.append("maxDownloadCount", _("maxDownloadCount").value);
        }
        if (_("setDateLimit").checked) {
            request_key.append("setDateLimit", "true");
            request_key.append("DownloadLimit", _("DownloadLimit").value);
        }
        
        fetch(
          window.end2endtech.Endpoint + "get-overridablekey",
          {
            body: request_key,
            method: "POST",
            headers: {
              Accept: "application/json"
            },
            cache: 'no-cache'
          }
        )
        .then(
          e => e.json()
        )
        .then(
          d => callback(d)
        )

    }

    function __sendPieceOfFile(pieceOfFile) {
     
        return fetch(
            window.end2endtech.Endpoint + "override",
            {
              body: pieceOfFile,
              method: "POST",
              headers: {
                Accept: "application/json"
              },
              cache: 'no-cache'
            }
          )
          .then(
            e => e.json()
          )

    }

    function createNewURL(FileDetails) {

        if (!FileDetails["FileID"])
            return;

        if (_("resultTable").classList.contains("default-remove"))
            _("resultTable").classList.remove("default-remove");

        var e = _("resultTable").tBodies[0].insertRow(-1);

        var filename = document.createTextNode(FileDetails["FileName"]);
        filename.className = "px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white";
        e.className = "height40 bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-3";
        e.insertCell(0).appendChild(filename);

        var anchor = document.createElement('a');
        anchor.appendChild(document.createTextNode(FileDetails["URL"]));
        anchor.href = FileDetails["URL"];
        anchor.target = "_blank";
        anchor.className = "px-6 py-3";
        e.insertCell(1).appendChild(anchor);

        var anchor2 = document.createElement('a');
        anchor2.appendChild(document.createTextNode("ファイルを共有"));
        anchor2.href = "javascript:window.shareURL(\"" + atk.encode(FileDetails["URL"]) + "\", \"" + atk.encode(FileDetails["FileName"]) + "\");";
        anchor2.className = "px-6 py-3";
        e.insertCell(2).appendChild(anchor2);

        var pwd = document.createTextNode(FileDetails["RemovePassword"]);
        pwd.className = "px-6 py-3";
        e.insertCell(3).appendChild(pwd);
    }

    window.shareURL = function (encodedurl, filename) {
        var url = atk.decode(encodedurl);
        if (!window.navigator.share) {
            atk.copy(url);
            alert("URLをコピーしました！");
        }
        else {
            window.navigator.share({
                title: filename,
                url: url
            });
        }
    }


}(window));