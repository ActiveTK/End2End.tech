/*!
 * index.js - End2End.tech
 */

"use strict";
(function (window, undefined) {

    function _(targetName) {
        return document.getElementById(targetName);
    }

    document.addEventListener("DOMContentLoaded", function () {

        _("main").style.display = "block";

        _("setLimitDownload").onchange = function () {
            _("setLimitDownloadInput").classList.toggle("default-remove");
        }
        _("setPassword").onchange = function () {
            _("setPasswordInput").classList.toggle("default-remove");
        }

    });


}(window));