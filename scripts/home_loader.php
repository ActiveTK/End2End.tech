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
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script>
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/pbkdf2.js"></script>
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
            <br><br>
            <p>ここにファイルをドラッグ&ドロップして下さい。</p>
            <br>
            <p>または、ファイルを選択(100MB以内):</p>
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
                <input name="maxDownloadCount" type="number" class="maxDownloadCount border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition focus:ring" value="0" />
              </span>
            </div>
            <br>
            <div class="sm:col-span-2">
              <input type="checkbox" id="setDateLimit" name="setDateLimit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              ダウンロード期限を設定する<br>
              <span id="setDateLimitInput" class="default-remove">
                ダウンロード期限(JST):
                <input type="datetime-local" name="DownloadLimit" step="300">
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
        <div class="textblack" align="center">
          <br>
          <input type="submit" name="submitData" class="inline-block rounded-lg bg-indigo-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-indigo-600 focus-visible:ring active:bg-indigo-700 md:text-base" value="利用規約に同意してアップロード">
          <p id="stat"></p>
        </div>
      </form>
    </div>

    <?php require_once("../scripts/footer.php"); ?>

  </body>
</html>
