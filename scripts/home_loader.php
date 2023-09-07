<?php
  
  // サイズを適切な単位に変換する関数
  function byte_format($size, $dec=-1, $separate=false){
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $digits = ($size == 0) ? 0 : floor( log($size, 1024) );
	
    $over = false;
    $max_digit = count($units) - 1;

    if($digits == 0)
      $num = $size;
    else if (!isset($units[$digits])) {
      $num = $size / (pow(1024, $max_digit));
      $over = true;
    } else
      $num = $size / (pow(1024, $digits));
	
    if($dec > -1 && $digits > 0) $num = sprintf("%.{$dec}f", $num);
    if($separate && $digits > 0) $num = number_format($num, $dec);
	
    return ($over) ? $num . $units[$max_digit] : $num . $units[$digits];
  }

?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title>End2End.tech - Anonymous File Uploader</title>
    <meta name="robots" content="All">
    <meta name="description" content="匿名で簡単にファイルをアップロードできます。End2End暗号化対応・ノーログで、安心安全のオープンソースです。">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/js/pswmeter.js"></script>
    <script src="https://code.activetk.jp/ActiveTK.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script>window.end2endtech={Endpoint:atk.decode("<?=urlencode(APIEndPoint)?>")};</script>
    <script src="/js/index.js"></script>
    <link href="/css/index.css" rel="stylesheet">
  </head>
  <body>

    <br>
    <h1 class="text-3xl font-bold" align="center">
      End2End.tech - Anonymous File Uploader
    </h1>
    <br>

    <noscript>
      <div align="center">
        <h1>このページを表示するには、JavaScriptを有効化して下さい。</h1>
      </div>
    </noscript>

    <div id="main" class="main py-6 sm:py-8 lg:py-12">
      <form action="" enctype="multipart/form-data" method="POST" class="mx-auto max-w-screen-2xl px-4 md:px-8" id="uploader">
        <div class="textblack flex flex-col overflow-hidden rounded-lg bg-gray-200 sm:flex-row md:h-80">

          <div class="textcenter order-first h-48 w-full bg-gray-300 sm:order-none sm:h-auto sm:w-1/2 lg:w-1/2" id="uploadzone">
            <p class="mt-[3rem]">ここにファイルをドラッグ&ドロップして下さい。</p>
            <p class="mt-5">または、ファイルを選択(<?=byte_format(MaxUploadSize, 0)?>以内):</p>
            <input id="file" name="file" type="file" required>
          </div>

          <div class="flex w-full flex-col p-4 sm:w-1/2 sm:p-8 lg:w-1/2">
            <div class="sm:col-span-2">
              <input type="checkbox" id="setPassword" name="setPassword" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              データのE2E暗号化パスワードを設定する<br>
              <span id="setPasswordInput" class="default-remove">
                パスワード:
                <input name="passwd" id="password" class="rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" placeholder="p@ssword" />
                <div id="pswmeter" class="pswmeter default-remove py-2"></div>
                <div id="pswmetermsg" class="default-remove"></div>
              </span>
            </div>
            <br>
            <div class="sm:col-span-2">
              <input type="checkbox" id="setLimitDownload" name="setLimitDownload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              最大ダウンロード回数を設定する<br>
              <span id="setLimitDownloadInput" class="default-remove">
                最大ダウンロード回数(0で無制限):
                <input name="maxDownloadCount" id="maxDownloadCount" type="number" class="maxDownloadCount border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition focus:ring" value="0" />
              </span>
            </div>
            <br>
            <div class="sm:col-span-2">
              <input type="checkbox" id="setDateLimit" name="setDateLimit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              ダウンロード期限を設定する<br>
              <span id="setDateLimitInput" class="default-remove">
                ダウンロード期限(JST):
                <input type="datetime-local" id="DownloadLimit" name="DownloadLimit">
              </span>
            </div>
            <br>
            <div class="sm:col-span-2">
              <input type="checkbox" id="blockVPN" name="blockVPN" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              TorやVPN経由のダウンロードをブロックする
            </div>
          </div>
        </div>
        <br>
        <div align="center">
          <br>
          <input type="submit" name="submitData" id="submitData" class="inline-block rounded-lg bg-indigo-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-indigo-600 focus-visible:ring active:bg-indigo-700 md:text-base" value="利用規約に同意してアップロード">
          <p id="stat"></p>
        </div>
        <div align="center">
          <br>
          <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 default-remove" id="resultTable">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th class="px-6 py-3">ファイル名</th>
                <th class="px-6 py-3">URL</th>
                <th class="px-6 py-3">削除パスワード</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </form>
    </div>

    <?php require_once("../scripts/footer.php"); ?>

  </body>
</html>
