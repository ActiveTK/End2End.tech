<?php

  if ( !defined( "FileInfo" ) ) {
    header( "HTTP/1.1 500 Internal Server Error" );
    die( "内部エラーが発生しました。" );
  }
  
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

  // 各種情報
  $FileID = FileInfo["FileID"];
  $FileName = htmlspecialchars( basename( FileInfo["FileName"] ) );
  $FileHash = FileInfo["FileHash"];
  $FileSize = byte_format( FileInfo["FileSize"] , 2, true );

  if ( !empty( FileInfo["FileDownloadLimit"] ) && FileInfo["FileDownloadLimit"] * 1 != 0 ) {
    if ( FileInfo["FileDownloadLimit"] * 1 <= FileInfo["DownloadCount"] * 1 )
      define( "DownloadBlocked", "アップロードしたユーザーが設定したファイルのダウンロード制限回数を超えたため、ファイルは無効となりました。" );
  }
  if ( !empty( FileInfo["FileValidDateLimit"] ) ) {
    if ( time() > FileInfo["FileValidDateLimit"] * 1 )
      define( "DownloadBlocked", "アップロードしたユーザーが設定したファイルのダウンロード期限を超えたため、ファイルは無効となりました。" );
  }
  if ( !empty( FileInfo["EndtoEndEncrypted"] ) && FileInfo["EndtoEndEncrypted"] == "true" ) {
    define( "DataEncrypted", true );
  }
  if ( !empty( FileInfo["BlockVPN"] ) && FileInfo["BlockVPN"] == "true" ) {
    $headers = getallheaders();
    if ( !isset( $headers["Cf-Ipcountry"] ) || $headers["Cf-Ipcountry"] != "JP" )
      define( "DownloadBlocked", "アップロードしたユーザーの設定により、VPN経由のダウンロードは禁止されています。" );

    $IP = isset( $headers["Cf-Connecting-Ip"] ) ? $headers["Cf-Connecting-Ip"] : $_SERVER["REMOTE_ADDR"];
    $ipoc = explode(".", $IP);
    if ( @gethostbyname( $ipoc[3] . "." . $ipoc[2] . "." . $ipoc[1] . "." . $ipoc[0] . ".dnsel.torproject.org" ) == "127.0.0.2" )
      define( "DownloadBlocked", "アップロードしたユーザーの設定により、Tor経由のダウンロードは禁止されています。" );
  }

?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title><?=$FileName?> - End2End.tech</title>
    <meta name="robots" content="All">
    <meta name="description" content="ファイル「<?=$FileName?>」のダウンロードページです。ファイルサイズは<?=$FileSize?>、ハッシュ<?=$FileHash?>です。">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.activetk.jp/ActiveTK.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script>window.end2endtech={FileID:"<?=$FileID?>",FileName:atk.decode("<?=urlencode(basename( FileInfo["FileName"] ))?>"),Encrypted:<?=(defined("DataEncrypted")?"true":"false")?>,Endpoint:atk.decode("<?=urlencode(APIEndPoint)?>"),EnableAPIAsSubDomain:<?=EnableAPIAsSubDomain?"true":"false"?>};</script>
    <script src="/js/download.js"></script>
    <link href="/css/download.css" rel="stylesheet">
  </head>
  <body>

    <br>
    <h1 class="text-3xl font-bold" align="center">
      <?=$FileName?> - End2End.tech
    </h1>
    <br>

    <noscript>
      <div align="center">
        <h1>このページを表示するには、JavaScriptを有効化して下さい。</h1>
      </div>
    </noscript>

    <div id="main" class="main py-6 sm:py-8 lg:py-12">

      <div class="mx-auto max-w-screen-md px-4 md:px-8">
        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4">【ファイルの概要】</h1>
        <p class="mb-6 sm:text-lg md:mb-8">
          ファイル名: <?=$FileName?><br>
          ファイルサイズ: <?=$FileSize?><br>
          ハッシュ(SHA256): <?=$FileHash?><br>
          アップロード日時: <?=date("Y/m/d - M (D) H:i:s", FileInfo["UploadDate"])?>
        </p>

        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4">【ファイルをダウンロード】</h2>
        <p class="mb-6 sm:text-lg md:mb-8">
        
          <?php if ( defined( "DownloadBlocked" ) ) { ?>
            <?=DownloadBlocked?>
          <?php } else { ?>
            <?php if ( defined( "DataEncrypted" ) ) { ?>
              ファイルはEnd-to-End暗号化されており、複合化にはパスワードが必要です。
              <div class="flex">
                <input type="text" id="password" placeholder="複合化パスワード" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <input type="button" id="downloadData" class="inline-block rounded-lg bg-indigo-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-indigo-600 focus-visible:ring active:bg-indigo-700 md:text-base" value="ファイルをダウンロード">
              </div>
            <?php } else { ?>
              <input type="button" id="downloadData" class="inline-block rounded-lg bg-indigo-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-indigo-600 focus-visible:ring active:bg-indigo-700 md:text-base" value="ファイルをダウンロード">
            <?php } ?>
          <?php } ?>

          <br>
          <span id="stat"></span>
          <br>
        </p>

        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4">【ファイルを削除】</h1>
        <p class="mb-6 sm:text-lg md:mb-8">
          サーバーからファイルを完全に削除できます。
          <div class="flex">
            <input type="text" id="remove-password" placeholder="削除用パスワード" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <button class="bg-gray-700 hover:bg-gray-600 text-white rounded px-4 py-2" id="removefile">ファイルを削除</button><br>
          </div>
          <span id="statRemove"></span>
        </p>

      </div>
    </div>

    <?php require_once("../scripts/footer.php"); ?>

  </body>
</html>
