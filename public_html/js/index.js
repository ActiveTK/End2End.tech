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
            if (files.length > 1)
               return alert('ファイルは一つまで選択できます。');
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

            if (_("file").files.length != 1)
                return alert('ファイルを選択して下さい。');

            _("submitData").disabled = true;
            _("stat").innerText = "ファイルをアップロードしています..。";

            _("password").removeAttribute("name");

            if (!_("setLimitDownload").checked)
                _("maxDownloadCount").removeAttribute("name");

            if (!_("setDateLimit").checked)
                _("DownloadLimit").removeAttribute("name");

            if (_("setPassword").checked) {

                if (!_("password").value)
                    return alert("パスワードを指定して下さい。");

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
                    _("file").files = list.files;

                    enc = "";

                    sendFile(new FormData($("#uploader").get(0)));
                };
            }
            else {
              sendFile(new FormData($("#uploader").get(0)));
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

                    _("submitData").disabled = false;

                    _("password").setAttribute("name", "passwd");

                    if (!_("setLimitDownload").checked)
                        _("maxDownloadCount").setAttribute("name", "maxDownloadCount");

                    if (!_("setDateLimit").checked)
                        _("DownloadLimit").setAttribute("name", "DownloadLimit");
                }
                else if (b == "Status" && t["Status"] == "OK") {
                    _("stat").innerText = "";

                    try {
                        _("file").value = "";
                    }
                    catch { }

                    createNewURL(t);

                    _("submitData").disabled = false;

                    _("password").setAttribute("name", "passwd");

                    if (!_("setLimitDownload").checked)
                        _("maxDownloadCount").setAttribute("name", "maxDownloadCount");

                    if (!_("setDateLimit").checked)
                        _("DownloadLimit").setAttribute("name", "DownloadLimit");
                }
            }

        }).fail(function (t, e, o) {

            _("stat").innerText = "送信中にエラーが発生しました:" + o;
            _("submitData").disabled = false;

            _("password").setAttribute("name", "passwd");

            if (!_("setLimitDownload").checked)
                _("maxDownloadCount").setAttribute("name", "maxDownloadCount");

            if (!_("setDateLimit").checked)
                _("DownloadLimit").setAttribute("name", "DownloadLimit");

        });

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

        var pwd = document.createTextNode(FileDetails["RemovePassword"]);
        pwd.className = "px-6 py-3";
        e.insertCell(2).appendChild(pwd);

        var anchor2 = document.createElement('a');
        anchor2.appendChild(document.createTextNode("ファイルを共有"));
        anchor2.href = "javascript:window.shareURL(\"" + atk.encode(FileDetails["URL"]) + "\", \"" + atk.encode(FileDetails["FileName"]) + "\");";
        anchor2.className = "px-6 py-3";
        e.insertCell(3).appendChild(anchor2);

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