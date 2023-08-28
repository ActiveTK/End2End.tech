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

            _("stat").innerText = "ファイルを送信中..。";

            if (_("setPassword").checked) {
                let reader = new FileReader();
                reader.readAsBinaryString(_("file").files[0], 'UTF-8');
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
                    console.log(enc);
                    _("password").removeAttribute("name");

                    let list = new DataTransfer();
                    let file = new File([enc], _("file").files[0].name);
                    list.items.add(file);
                    _("file").files = list.files;

                    enc = "";

                    sendFile(new FormData($("#uploader").get(0)));
                };
            }
            else
            {
                _("password").removeAttribute("name");
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
                        _("stat").innerText = "   M   B B(" + (evt.loaded / evt.total * 100).toFixed(2) + "%    )";
                }, false);
                return xhr;
            },
            url: "https://api.end2end.tech/upload",
            type: "POST",
            data: Data,
            cache: !1,
            contentType: !1,
            processData: !1,
            dataType: "json"

        }).done(function (t) {

            for (let b in t) {
                if (b == "error") {
                    _("stat").innerText = t[b];
                }
                else {
                    window.location.href = "/" + ["hash"];
                }
            }

        }).fail(function (t, e, o) {

            _("stat").innerText = "送信中にエラーが発生しました:" + o;

        });

    }


}(window));