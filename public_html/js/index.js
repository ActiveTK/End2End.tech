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
               return alert('同時にアップロードできるファイルは1つまでです。');
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
            sendFile();
            return false;
        }

    });

    function sendFile(Data) {

        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable)
                        _("stat").innerText = "送信中。。(" + (evt.loaded / evt.total * 100).toFixed(2) + "%完了)";
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
                    window.location.href = "file?q=" + t["hash"];
                }
            }

        }).fail(function (t, e, o) {

            _("stat").innerText = "送信に失敗しました。詳細:" + o;

        });

    }


}(window));