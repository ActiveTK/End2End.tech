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
    });



}(window));