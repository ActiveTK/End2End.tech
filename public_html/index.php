<?php

  /*!
   * End2End.tech - Anonymous File Uploader
   * (c) 2023 ActiveTK.
   * Released under the MIT license.
   */

  // 読み込み開始時刻を記録
  define( "LOAD_START_TIME", microtime( true ) );

  // 設定取得
  require_once( "../Config.php" );

  // HTTPSの自動リダイレクト処理
  if ( EnableSSL && empty( $_SERVER['HTTPS'] ) ) {
    header( "Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" );
    die();
  }

  // 必須ヘッダーの出力
  header( "X-Frame-Options: deny" );
  header( "Access-Control-Allow-Origin:" );
  header( "Strict-Transport-Security: max-age=63072000; preload" );
  header( "X-XSS-Protection: 1; mode=block" );
  header( "X-Content-Type-Options: nosniff" );
  header( "X-Permitted-Cross-Domain-Policies: none" );
  header( "Referrer-Policy: same-origin" );

  // パスの取得
  // リクエストURIから先頭のスラッシュを削除したもの
  // ex: (GET /hoge/index.php HTTP/1.1) => request_path === "hoge/index.php"
  define( "request_path", ( isset( $_GET["request"] ) && is_string( $_GET["request"] ) ) ? strtolower( $_GET["request"] ) : "" );

  // パスの末尾がスラッシュである場合の処理
  if ( strlen( request_path ) > 0 && substr( request_path, -1 ) == "/" ) {
    header( "Location: https://end2end.tech/" . strtolower( substr( $_GET["request"], 0, -1 ) ), true, 301 );
    exit();
  }

  // HTMLを圧縮する関数
  function sanitize_output($buffer) {

    // XSS攻撃を防止するランダムな英数列
    define( "FLG", substr( base_convert( sha1( md5( uniqid() ) . md5 ( microtime() ) ), 16, 36), 0, 5) );

    // Content-TypeがHTMLでない場合の処理
    foreach(headers_list() as $line)
    {
      list($title, $data) = explode(": ", $line, 2);
      if (strtolower($title) == "content-type" && false === strpos($data, "text/html"))
        return $buffer;
    }

    // 中身の改行が意味を持つタグの処理
    $buffer = preg_replace_callback("/<pre.*?<\/pre>/is", function($matches) {
      return "_" . FLG . "_here___prf__start" . base64_encode(urlencode($matches[0])) . "_" . FLG . "_here___prf__end";
    }, $buffer);
    $buffer = preg_replace_callback("/<script.*?<\/script>/is", function($matches) {
      return "_" . FLG . "_here___sct__start" . base64_encode(urlencode($matches[0])) . "_" . FLG . "_here___sct__end";
    }, $buffer);
    $buffer = preg_replace_callback("/<textarea.*?<\/textarea>/is", function($matches) {
      return "_" . FLG . "_here___txs__start" . base64_encode(urlencode($matches[0])) . "_" . FLG . "_here___txs__end";
    }, $buffer);

    // 改行や空白を削除
    $buffer = preg_replace(array("/\>[^\S]+/s", "/[^\S]+\</s", "/(\s)+/s" ), array(">", "<", " "), $buffer);

    // 改行が意味を持つタグを元に戻す処理
    $buffer = preg_replace_callback("/_" . FLG . "_here___prf__start.*?_" . FLG . "_here___prf__end/is", function($matches) {
      return urldecode(base64_decode(substr(substr($matches[0], 24), 0, -22)));
    }, $buffer);
    $buffer = preg_replace_callback("/_" . FLG . "_here___sct__start.*?_" . FLG . "_here___sct__end/is", function($matches) {
      return urldecode(base64_decode(substr(substr($matches[0], 24), 0, -22)));
    }, $buffer);
    $buffer = preg_replace_callback("/_" . FLG . "_here___txs__start.*?_" . FLG . "_here___txs__end/is", function($matches) {
      return urldecode(base64_decode(substr(substr($matches[0], 24), 0, -22)));
    }, $buffer);

    // DOCTYPE宣言の後にコメントを追加
    if (substr($buffer, 0, 15) == "<!DOCTYPE html>")
      $buffer = substr($buffer, 15);

    return
      "<!DOCTYPE html><!--\n" .
        "\n" .
        "  End2End.tech / (c) 2023 ActiveTK.\n\n" .
        "  Server-Side Time: " . ( microtime( true ) - LOAD_START_TIME ) . "s\n" .
        "  Cached Date: " . ( new DateTime( "now", new DateTimeZone( "GMT" ) ) ) -> format( "Y-m-d H:i:sP" ) . "\n" .
      "\n-->" . $buffer . "\n";

    return $buffer . "\n";
  }

  // 出力したバッファーを自動で圧縮するように設定
  ob_start( "sanitize_output" );

  // ホーム
  if ( empty( request_path ) ) {
    require_once( "../scripts/home_loader.php" );
    exit();
  }
  else if ( request_path == "about" ) {
    require_once( "../scripts/about.php" );
    exit();
  }
  else if ( request_path == "license" ) {
    require_once( "../scripts/license.php" );
    exit();
  }
  else if ( request_path == "contact" ) {
    require_once( "../scripts/contact.php" );
    exit();
  }
  else if ( request_path == "donate" ) {
    require_once( "../scripts/donate.php" );
    exit();
  }
  else if ( request_path == "js/pswmeter.js" ) {
    header( "Content-Type: text/javascript" );
    readfile( "../lib/pswmeter.js" );
    exit();
  }
  else if ( request_path == "js/aes.js" ) {
    header( "Content-Type: text/javascript" );
    readfile( "../lib/CryptoJS/components/aes.js" );
    exit();
  }
  else if ( request_path == "js/inflate.js" ) {
    header( "Content-Type: text/javascript" );
    readfile( "../lib/inflate.js" );
    exit();
  }
  else if ( request_path == "js/deflate.js" ) {
    header( "Content-Type: text/javascript" );
    readfile( "../lib/deflate.js" );
    exit();
  }

  // 指定されたファイルIDが存在するか確認
  $Note = array();
  try {
    $dbh = new PDO( DSN, DB_USER, DB_PASS );
    $stmt = $dbh->prepare( 'select * from UploadFiles where FileID = ?;' );
    $stmt->execute( [request_path] );
    $resd = $stmt->fetch( PDO::FETCH_ASSOC );
    if ($resd !== false)
      $Note = $resd;
    else
    {
      // ファイルが存在しない場合
      header( "HTTP/1.1 404 Not Found" );
      die( "HTTP 404 - Not Found" );
    }
    unset($resd);
  } catch (\Throwable $e) {
    die("SQLエラーが発生しました。");
  }
  
  define( "FileInfo", $Note );
  require_once( "../scripts/download-loader.php" );
