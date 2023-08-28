<?php

  header( "Content-Type: application/json" );
  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

  // ランダムな英数字を取得する関数
  function GetRand( int $len = 32 ) {

    $bytes = openssl_random_pseudo_bytes( $len / 2 );
    $str = bin2hex( $bytes );

    $usestr = '1234567890abcdefghijklmnopqrstuvwxyz';
    $str2 = substr( str_shuffle( $usestr ), 0, 12 );

    return substr( str_shuffle( $str . $str2 ) , 0, -12 );

  }

  if( isset( $_FILES['file'] ) && is_uploaded_file( $_FILES['file']['tmp_name'] ) ) {

    $Size = filesize( $_FILES['file']['tmp_name'] );
    if( $Size > 1024 * 1024 * 100 )
      die( json_encode( array( "Error"=>"エラー: ファイルサイズが100MBを超えています。(ERR_FILE_TOO_BIG)" ), JSON_UNESCAPED_UNICODE ) );
    
    $FileID = substr( hash_file( 'md5', $_FILES['file']['tmp_name'] ), 0, 4 ) . dechex(time());
    $RemovePassword = GetRand( 8 );

    if ( file_exists( "{$basepath}{$FileID}" ) )
      die( json_encode( array( "Error"=>"エラー: このファイルは他の方によって既にアップロードされています。(ERR_FILE_EXISTS)" ), JSON_UNESCAPED_UNICODE ) );

    $Hash = hash_file( 'sha256', $_FILES['file']['tmp_name'] );

    $DownloadLimit = "0";
    if ( isset( $_POST["setLimitDownload"] ) && isset( $_POST["maxDownloadCount"] ) ) {

      if ( !is_numeric( $_POST["maxDownloadCount"] ) || $_POST["maxDownloadCount"] * 1 < 0 )
        die( json_encode( array( "Error"=>"エラー: 最大ダウンロード回数には、非負整数のみ指定できます。" ), JSON_UNESCAPED_UNICODE ) );

      $DownloadLimit = $_POST["maxDownloadCount"];

    }

    $BlockVPN = "false";
    if ( isset( $_POST["blockVPN"] ) ) {
        $BlockVPN = "true";
    }

    $IsEncrypted = "false";
    if ( isset( $_POST["setPassword"] ) ) {
        $IsEncrypted = "true";
    }

    $DateLimit = "";
    if ( isset( $_POST["setDateLimit"] ) && isset( $_POST["DownloadLimit"] ) ) {
        $DateLimit = @strtotime( $_POST["DownloadLimit"] );
    }

    $FileName = $_FILES['file']['name'];

    try {

      $dbh = new PDO( DSN, DB_USER, DB_PASS );
      $stmt = $dbh->prepare(
        "insert into UploadFiles(
          FileID, FileName, FileSize, FileHash, DownloadCount, UploadDate, FileDownloadLimit, FileValidDateLimit, EndtoEndEncrypted, BlockVPN, DeletePassword
        )
        value(
          ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )"
      );
      $stmt->execute( [
        $FileID,
        $FileName,
        $Size,
        $Hash,
        "0",
        time(),
        $DownloadLimit,
        $DateLimit,
        $IsEncrypted,
        $BlockVPN,
        $RemovePassword
      ] );

    } catch (\Throwable $e) {
      die( json_encode( array( "Error"=>"エラー: アップロードの処理に失敗しました。(ERR_SQL_FAILED)" ), JSON_UNESCAPED_UNICODE ) );
    }

    file_put_contents(
        "{$basepath}{$FileID}",
        gzdeflate(
            file_get_contents( $_FILES['file']['tmp_name'] ),
            9
        )
    );

    exit(
        json_encode(
            array(
                "Status" => "OK",
                "FileID" => $FileID,
                "FileName" => basename( $FileName ),
                "URL" => "https://end2end.tech/" . $FileID,
                "SHA256" => $Hash,
                "RemovePassword" => $RemovePassword
            )
        )
    );

  }

  die( json_encode( array( "Error"=>"エラー: アップロードに失敗しました。(ERR_FILE_NOTSELECTED)" ), JSON_UNESCAPED_UNICODE ) );
