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

        const pswmeter = passwordStrengthMeter({
            containerElement: '#pswmeter',
            passwordInput: '#password',
            showMessage: true,
            messageContainer: '#pswmetermsg',
            borderRadius: 2
        });

        _("password").onchange = function () {
            if (_("password").value) {
                _("pswmeter").display.style = "inline";
                _("pswmetermsg").display.style = "inline";
            }
        }

    });


}(window));