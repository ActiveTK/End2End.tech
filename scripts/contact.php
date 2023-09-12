<?php

  if ( isset( $_POST["data"] ) && isset( $_POST["email"] ) && isset( $_POST["title"] ) && isset( $_POST["name"] ) )
  {
    $dec = $_POST["title"];
    $mail = $_POST["email"];
    $datax = $_POST["data"];
    $name = $_POST["name"];

    $LogFile = "../objects/contact.txt";

    $debuginfo = array();

    $debuginfo["Time"] = date( "Y/m/d - M (D) H:i:s" );
    $debuginfo["Time_Unix"] = microtime( true );

    $debuginfo["Dec"] = $dec;
    $debuginfo["Mail"] = $mail;
    $debuginfo["Data"] = $datax;
    $debuginfo["Name"] = $name;

    $a = fopen( $LogFile, "a" );
    @fwrite( $a, json_encode( $debuginfo ) . "\n" );
    fclose( $a );

    NotificationAdmin( "お問い合わせ: " . htmlspecialchars( $dec ),
      "<p>送信時刻: " . date( "Y/m/d - M (D) H:i:s" ) . "</p>" .
      "<hr color='#363636' size='2'><p>名前: " . htmlspecialchars( $name ) . "</p><p>返信先メールアドレス: " . htmlspecialchars( $mail ) . "</p><p>内容</p><pre>" . htmlspecialchars( $datax ) . "</pre><br>");
    
    ?>
        <meta name="robots" content="noindex, nofollow">
        <body style="background-color:#e6e6fa;">
          <h1>お問い合わせを受け付けました。</h1>
          <p><b>指定されたメールアドレスに返信をお返しすると共に、このデータは、<a href="/">利用規約</a>に基づき、サービスの改善に使用させていただきます。<br>また、返信は一週間程度の時間を要する場合がございますので、ご了承ください。</b></p>
          <h3><a href="/">ホームへ戻る</a></h3>
        </body>
      <?php

    exit();

  }
?>
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

        <p class="mb-6 sm:text-lg md:mb-8">
          本サービスでは、以下のフォームからお問い合わせを受け付けております。<br>
          お問い合わせ頂いた内容は<a href="/privacy" target="_blank" style="color:#00ff00;">プライバシーポリシー</a>に基づき、サービスの改善に利用させて頂きます。
        </p>

        <p class="mb-6 sm:text-lg md:mb-8">
          <form action="" enctype="multipart/form-data" method="post">
            タイトル: <input type="text" class="rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" style="width:100%;" name="title" maxlength="120" placeholder="ここにタイトルを入力してください(120文字まで)" required>
            <br><br>
            お名前: <input type="text" class="rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" name="name" style="width:100%;" maxlength="120" placeholder="ニックネームを入力して下さい" required>
            <br><br>
            メールアドレス: <input type="email" class="rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" name="email" style="width:100%;" maxlength="120" placeholder="メールアドレスを入力して下さい" required>
            <br><br>
            内容:<br>
            <textarea name="data" maxlength="1080" placeholder="ここに内容を入力してください(1080文字まで)" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" style="height:400px;" required></textarea>
            <br>
            <pre>※本フォーム内に個人情報を入力しないで下さい。
また、お問い合わせの内容に機密情報が含まれる場合には、<a href="/pgp.asc" download="end2end_tech.asc" style="color:#00ff00;">PGP公開鍵</a>で暗号化して送信することもできます。</pre><br>
            <input type="submit" value="送信" style="width:73px;height:33px;color:#000000;background-color:#90ee90;"><br>
          </form>
        </p>

      </div>
    </div>

    <?php require_once("../scripts/footer.php"); ?>

  </body>
</html>
