<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title>API - End2End.tech</title>
    <meta name="robots" content="All">
    <meta name="description" content="APIのドキュメンテーションです。">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://www.activetk.jp/js/prism.js"></script>
    <script src="https://end2end.tech/js/min.js"></script>
    <link href="https://www.activetk.jp/css/prism.css" rel="stylesheet">
    <link href="https://end2end.tech/css/download.css" rel="stylesheet">
  </head>
  <body>

    <br>
    <h1 class="text-3xl font-bold" align="center">
      API - End2End.tech
    </h1>
    <br>

    <noscript>
      <div align="center">
        <h1>このページを表示するには、JavaScriptを有効化して下さい。</h1>
      </div>
    </noscript>

    <div id="main" class="main py-6 sm:py-8 lg:py-12">
      <div class="mx-auto max-w-screen-md px-4 md:px-8">

        <p class="mb-6 sm:text-lg md:mb-8">
          End2End.techでは、外部のスクリプトやコマンドラインから簡単にファイルをアップロードできるAPIを用意しています。
          これらのAPIの利用に登録は必要ありませんが、極端にサーバーへ負荷をかける行為やスクレイピングなどはお止めください。
        </p>

        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4">ファイルの新規アップロード</h1>
        <p class="mb-6 sm:text-lg md:mb-8">
          ファイルを新規にアップロードするには、以下のURLにPOSTリクエストを送信して下さい。
        </p>

        <pre class="line-numbers"><code class="language-Bash">curl https://api.end2end.tech/upload
  -X POST
  -F file=@/fakepath/helloworld.png</code></pre>

        <br>

        <p class="mb-6 sm:text-lg md:mb-8">
          レスポンスは以下のようになります。
        </p>

        リクエストに成功した場合:
        <pre class="line-numbers"><code class="language-Json">{
  "Status": "OK",
  "FileID": "ファイルID",
  "FileName": "ファイル名",
  "URL": "ファイルのダウンロード用URL",
  "SHA256": "ファイルのSHA256ハッシュ",
  "RemovePassword": "ファイルの削除パスワード"
}</code></pre>

        リクエストに失敗した場合:
        <pre class="line-numbers"><code class="language-Json">{
  "Error": "エラーの詳細メッセージ"
}</code></pre>

        <br>

        <p class="mb-6 sm:text-lg md:mb-8">
          また、以下のようにリクエストにオプションを付属させることもできます。
        </p>

        <pre class="line-numbers"><code class="language-Bash">curl https://api.end2end.tech/upload
  -X POST
  -F file=@/fakepath/helloworld.png
  -F 'setLimitDownload=on'
  -F 'maxDownloadCount=100'
  -F 'blockVPN=on'</code></pre>

        <br>

        <p class="mb-6 sm:text-lg md:mb-8">
          現在、対応しているオプションは以下の通りです。
        </p>

        <li>blockVPN: onに設定すると、VPNやTorを経由したファイルのダウンロードを拒否します。</li>
        <li>setLimitDownload: onに設定すると、ファイルの最大ダウンロード回数を設定できます。maxDownloadCountと組み合わせて使用して下さい。</li>
        <li>maxDownloadCount: ファイルの最大ダウンロード回数を数値で指定できます。利用には、setLimitDownloadが必須です。</li>
        <li>setDateLimit: onに設定すると、ファイルのダウンロード期限を設定できます。DownloadLimitと組み合わせて使用して下さい。</li>
        <li>DownloadLimit: ファイルのダウンロード期限を指定できます。strtotimeで処理できる形式で指定して下さい。利用には、DownloadLimitが必須です。</li>

        <br>

        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4">ファイルのダウンロード</h1>
        <p class="mb-6 sm:text-lg md:mb-8">
          ファイルをダウンロードするには、以下のURLにGETリクエストを送信して下さい。
        </p>

        <pre class="line-numbers"><code class="language-Bash">curl https://api.end2end.tech/download?id={ファイルID} -o {出力先ファイル名}</code></pre>

        <br>

        <p class="mb-6 sm:text-lg md:mb-8">
          ただし、{ファイルID}にはアップロード時のFileIDを指定し、{出力先ファイル名}の指定は任意です。
        </p>

        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4">ファイルの削除</h1>
        <p class="mb-6 sm:text-lg md:mb-8">
          ファイルを削除するには、以下のURLにGETリクエストを送信して下さい。
        </p>

        <pre class="line-numbers"><code class="language-Bash">curl https://api.end2end.tech/delete?id={ファイルID}&password={削除用パスワード}</code></pre>

        <br>

        <p class="mb-6 sm:text-lg md:mb-8">
          ただし、{ファイルID}にはアップロード時のFileIDを指定し、{削除用パスワード}にはアップロード時のRemovePasswordを指定して下さい。<br>
          この操作は取り消せず、ディスク及びデータベースから完全にファイルが消去されますので、注意して下さい。
        </p>

      </div>
    </div>

    <?php require_once("../../scripts/footer.php"); ?>

  </body>
</html>
