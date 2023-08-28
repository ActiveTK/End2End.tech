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
				DownloadFile(window.end2endtech.FileID, function (result) {
					_("stat").innerText = "ファイルを複合化しています..。";
					window.navigator.msSaveBlob(new Blob([result], { "type": "application/force-download" }), window.end2endtech.FileName);
				});
			}
			else {
				DownloadFile(window.end2endtech.FileID, function (result) {
					window.navigator.msSaveBlob(new Blob([result], { "type": "application/force-download" }), window.end2endtech.FileName);
				});
			}
        }

    });

	function DownloadFile(fileid, callback) {

		var xhr = new XMLHttpRequest
		xhr.open('GET', 'https://api.end2end.tech/download?id=' + fileid);
		xhr.onprogress = function (evt) {
			_("stat").innerText = "ダウンロード中..(" + (100 * evt.loaded / evt.total | 0) + "%完了)..。";
		};
		xhr.onreadystatechange = function (evt) {
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					_("stat").innerText = "ダウンロードが完了しました。";
					callback(xhr.responseText);
				}
			}
		}
		xhr.send();

    }

}(window));