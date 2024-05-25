<?php

  /*!
   *
   * Config.php - end2end.tech
   * (c) 2023 ActiveTK.
   *
   * Released under the MIT license.
   *
   */

  /* -------------------------------- */
  /* -------- 設定はここから -------- */
  /* -------------------------------- */

  /* システム全般の設定 */

  // ドメイン名
  define( "Domain", "localhost:3000" );

  // TLSの有無
  define( "EnableSSL", false );

  // 「お問い合わせ」の通知先メールアドレス
  define( "NotificationEmail", "notification@localhost" );

  // APIをサブドメインで有効にする
  define( "EnableAPIAsSubDomain", false );
  
  // アップロードできる最大サイズ
  define( "MaxUploadSize", 1024 * 1024 * 100 );

  // デバッグモード
  define( "DEBUG", false );

  // 管理者の削除用パスワード
  define( "ForceRemovePassword", "password" );

  /* MySQLの設定 */

  // データベース名
  define( "DB_Name", "MyDatabase" );

  // データベースのホスト
  define( "DB_Host", "localhost" );

  // データベースの文字コード
  define( "DB_Charset", "UTF8" );

  // データベースのユーザー名
  define( "DB_USER", "root" );

  // データベースのパスワード
  define( "DB_PASS", "password" );

  /* -------------------------------- */
  /* -------- 設定はここまで -------- */
  /* -------------------------------- */

  mb_language( "Japanese" );
  mb_internal_encoding( "UTF-8" );

  define( "FullURL",
    ( EnableSSL ? "https" : "http" ) . "://" . Domain . "/"
  );
  define( "APIEndPoint", 
    ( EnableAPIAsSubDomain ?
      ( ( EnableSSL ? "https" : "http" ) . "://api." . Domain . "/" ) :
      ( ( EnableSSL ? "https" : "http" ) . "://" . Domain . "/api.end2end.tech/index.php?request=" )
    )
  );
  define( "DSN", "mysql:dbname=" . DB_Name . ";host=" . DB_Host . ";charset=" . DB_Charset );

  // デバッグモードの場合
  if ( defined( "DEBUG" ) ) {
    ini_set('display_errors', "On");
    ini_set( 'error_reporting', E_ALL );
  }

  // 管理者にメールで通知する関数
  function NotificationAdmin( string $title = "", string $str = "" ) {

    if ( empty( NotificationEmail ) )
      return;

    try{
      $body = '<body style="background-color:#e6e6fa;text:#363636;"><div align="center"><p>【' . htmlspecialchars( $title ) . '】</p><hr color="#363636" size="2">' . $str .
      '<br><hr color="#363636" size="2"><font style="background-color:#06f5f3;">Copyright &copy; 2023 ActiveTK. All rights reserved.</font></div></body>';
      define( "MAIL_SUBJECT", $title );
      define( "MAIL_BODY", $body );
      define( "MAIL_FROM_ADDRESS", "no-reply@" . Domain );
      define( "MAIL_FROM_NAME", "no-reply@" . Domain );
      define( "MAIL_HEADER",
        "Content-Type: text/html; charset=UTF-8 \n".
        "From: " . MAIL_FROM_NAME . "\n".
        "Sender: " . MAIL_FROM_ADDRESS ." \n".
        "Return-Path: " . MAIL_FROM_ADDRESS . " \n".
        "Reply-To: " . MAIL_FROM_ADDRESS . " \n".
        "Content-Transfer-Encoding: BASE64\n");
      @mb_send_mail( NotificationEmail, MAIL_SUBJECT, MAIL_BODY, MAIL_HEADER, "-f " . MAIL_FROM_ADDRESS );
    }
    catch (Exception $e) { }

  }


