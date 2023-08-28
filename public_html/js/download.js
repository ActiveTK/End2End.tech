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
					DownloadFileFromBlob(new Blob([result], { "type": "application/force-download" }), window.end2endtech.FileName);
					_("stat").innerText = "";
				});
			}
			else {
				DownloadFile(window.end2endtech.FileID, function (result) {
					DownloadFileFromBlob(new Blob([result], { "type": "application/force-download" }), window.end2endtech.FileName);
					_("stat").innerText = "";
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

	function DownloadFileFromBlob(blob, filename) {
		window.dataurl = window.URL.createObjectURL(blob);
		const a = document.createElement("a");
		document.body.appendChild(a);
		a.download = filename;
		a.href = window.dataurl;
		a.click();
		a.remove();
		setTimeout(function () {
			URL.revokeObjectURL(window.dataurl);
		}, 20000);
	}

}(window));