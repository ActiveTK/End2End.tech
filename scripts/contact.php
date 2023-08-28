<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title>お問い合わせ - End2End.tech</title>
    <meta name="robots" content="All">
    <meta name="description" content="お問い合わせフォームです。">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/js/min.js"></script>
    <link href="/css/download.css" rel="stylesheet">
  </head>
  <body>

    <br>
    <h1 class="text-3xl font-bold" align="center">
      お問い合わせ - End2End.tech
    </h1>
    <br>

    <noscript>
      <div align="center">
        <h1>このページを表示するには、JavaScriptを有効化して下さい。</h1>
      </div>
    </noscript>

    <div id="main" class="main py-6 sm:py-8 lg:py-12">
      <div class="mx-auto max-w-screen-md px-4 md:px-8">

        <h1 class="textblue mb-2 text-xl font-semibold sm:text-2xl md:mb-4"></h1>
        <p class="mb-6 sm:text-lg md:mb-8">
          <form action="" enctype="multipart/form-data" method="post">
            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="title" maxlength="120" placeholder="ここにタイトルを入力してください(120文字まで)" required>
            <br><br>
            <input type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="email" maxlength="120" placeholder="メールアドレスを入力して下さい" required>
            <br><br>
            <textarea name="data" maxlength="1080" placeholder="ここに内容を入力してください(1080文字まで)" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" style="height:400px;" required></textarea>
            <br>
            <input type="submit" value="送信" style="width:73px;height:33px;background-color:#90ee90;">
          </form>
        </p>

      </div>
    </div>

    <?php require_once("../scripts/footer.php"); ?>

  </body>
</html>
