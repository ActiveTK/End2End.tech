/*!
 * download.js - End2End.tech
 */

"use strict";
(function (window, undefined) {

    document.addEventListener("DOMContentLoaded", function () {

        _("main").style.display = "block";

		_("downloadData").onclick = function () {
			if (window.end2endtech.Encrypted === true) {

				if (window.decryptionarray) {
					_("stat").innerText = "ファイルを復号しています..。";

					var salt = CryptoJS.enc.Hex.parse(window.decryptionarray[0]);
					var iv = CryptoJS.enc.Hex.parse(window.decryptionarray[1]);
					try {
						DownloadFileFromBlobURL(
							CryptoJS.AES.decrypt(
								{
									"ciphertext": CryptoJS.enc.Base64.parse(window.decryptionarray[2])
								},
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
							).toString(CryptoJS.enc.Utf8),
							window.end2endtech.FileName
						);

						_("stat").innerText = "";
					} catch (e) {
						_("stat").innerText = "復号に失敗しました(" + e + ")。パスワードが合っているか、再度確認して下さい。";
					}
				}
				else {
					DownloadFile(window.end2endtech.FileID, function (result) {
						_("stat").innerText = "ファイルを復号しています..。";

						window.decryptionarray = [];
						window.decryptionarray[0] = String.fromCharCode.apply("", new Int8Array(result.slice(0, 32)));
						window.decryptionarray[1] = String.fromCharCode.apply("", new Int8Array(result.slice(33, 65)));

						window.decryptionarray[2] = "";
						var uint8Arr = new Int8Array(result.slice(66));
						for (var i = 0; i < uint8Arr.length; i += 1024)
							window.decryptionarray[2] += String.fromCharCode.apply(null, uint8Arr.slice(i, i + 1024));

						var salt = CryptoJS.enc.Hex.parse(window.decryptionarray[0]);
						var iv = CryptoJS.enc.Hex.parse(window.decryptionarray[1]);

						try {
							DownloadFileFromBlobURL(
							    CryptoJS.AES.decrypt(
							        {
									    "ciphertext": CryptoJS.enc.Base64.parse(window.decryptionarray[2])
									},
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
								).toString(CryptoJS.enc.Utf8),
								window.end2endtech.FileName
							);

							_("stat").innerText = "";
						} catch (e) {
							_("stat").innerText = "復号に失敗しました(" + e + ")。パスワードが合っているか、再度確認して下さい。";
						}
					});
				}
			}
			else {
				DownloadFile(window.end2endtech.FileID, function (result) {
					DownloadFileFromBlob(new Blob([result], { "type": "application/force-download" }), window.end2endtech.FileName);
					_("stat").innerText = "";
				});
			}
		}

		_("removefile").onclick = function () {
			if (!_("remove-password").value)
				return alert("削除用パスワードを入力して下さい。");
			if (!window.confirm("本当にファイルを削除しますか？\nファイルはサーバー上から完全に削除され、永久にアクセスできなくなります。"))
				return;

			var xhr = new XMLHttpRequest
			if (window.end2endtech.EnableAPIAsSubDomain)
				xhr.open('GET', window.end2endtech.Endpoint + 'delete?id=' + window.end2endtech.FileID + "&password=" + atk.encode(_("remove-password").value));
			else
				xhr.open('GET', window.end2endtech.Endpoint + 'delete&id=' + window.end2endtech.FileID + "&password=" + atk.encode(_("remove-password").value));
			xhr.responseType = 'json';
			xhr.onreadystatechange = function (evt) {
				if (xhr.readyState === 4) {
					if (xhr.status === 200) {
						_("statRemove").innerText = xhr.response.Message ? xhr.response.Message : xhr.response.Error;
					}
				}
			}
			xhr.send();
        }

    });

	function DownloadFile(fileid, callback) {

		let startDate = Date.now();
		var xhr = new XMLHttpRequest;
		if (window.end2endtech.EnableAPIAsSubDomain)
			xhr.open('GET', window.end2endtech.Endpoint + 'download?id=' + fileid);
		else
			xhr.open('GET', window.end2endtech.Endpoint + 'download&id=' + fileid);
		xhr.onprogress = function (evt) {

			let speed_guess = (evt.loaded / ((Date.now() - startDate) / 1000) / 1024 / 1024 * 8).toFixed(2);
		    let time_guess = ((evt.total - evt.loaded) / (speed_guess * 1024 * 1024)).toFixed();
	
			_("stat").innerHTML = "ダウンロード中..(" + (100 * evt.loaded / evt.total | 0) + "%完了)..。<br>" +
								  "通信速度: " + speed_guess + "Mbps, 推定残り時間: " + time_guess + "秒";
		};
    	xhr.responseType = "arraybuffer";
		xhr.onreadystatechange = function (evt) {
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					_("stat").innerText = "ダウンロードが完了しました。";
				    callback(xhr.response);
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

	function DownloadFileFromBlobURL(blob, filename) {
		const a = document.createElement("a");
		document.body.appendChild(a);
		a.download = filename;
		a.href = blob;
		a.click();
		a.remove();
	}

}(window));
