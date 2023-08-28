/*!
 * download.js - End2End.tech
 */

"use strict";
(function (window, undefined) {

    document.addEventListener("DOMContentLoaded", function () {

        _("main").style.display = "block";

        if (window.end2endtech.Encrypted === true) {

		}

		_("downloadData").onclick = function () {
			if (window.end2endtech.Encrypted === true) {

			}
			else {
				DownloadFile(window.end2endtech.FileID);
			}
        }

    });

	function DownloadFile(fileid) {

		var xhr = new XMLHttpRequest
		xhr.open('GET', 'https://api.end2end.tech/download?id=' + fileid);
		xhr.onprogress = function (evt) {
			_("stat").innerText = (100 * evt.loaded / evt.total | 0) + "%ダウンロード完了";
		};
		xhr.onreadystatechange = function (evt) {
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					alert('ダウンロード完了');
				}
			}
		}
		xhr.send();

    }

}(window));