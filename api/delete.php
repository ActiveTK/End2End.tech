<?php

  header( "Content-Type: application/json" );
  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

  if ( isset( $_GET["id"] ) && is_string( $_GET["id"] ) && isset( $_GET["password"] ) && is_string( $_GET["password"] ) ) {

    $Note = array();
    $dbh = new PDO( DSN, DB_USER, DB_PASS );
    try {

      $stmt = $dbh->prepare( 'select * from UploadFiles where FileID = ?;' );
      $stmt->execute( [$_GET["id"]] );
      $resd = $stmt->fetch( PDO::FETCH_ASSOC );
      if ($resd !== false)
        $Note = $resd;
      else
        die( json_encode( array( "Error"=>"エラー: 存在しないファイルIDです(ERR_FILE_NOTFOUND)" ), JSON_UNESCAPED_UNICODE ) );
      unset($resd);

      define( "FileInfo", $Note );

      if ( $_GET["password"] == FileInfo["DeletePassword"] ) {
        exit( json_encode( array( "Status"=>"OK", "Message"=>"ファイルの削除に成功しました。" ), JSON_UNESCAPED_UNICODE ) );
      }
      else {
        die( json_encode( array( "Error"=>"エラー: 削除パスワードが一致しません。(ERR_INCORRECT_PASSWORD)" ), JSON_UNESCAPED_UNICODE ) );
      }

    } catch (\Throwable $e) {
      die("SQLエラーが発生しました。");
    }

    exit();
  }

  die( json_encode( array( "Error"=>"エラー: 削除したいファイルのFileIDを指定して下さい。(ERR_FILE_NOTSELECTED)" ), JSON_UNESCAPED_UNICODE ) );
