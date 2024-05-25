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

  if( isset( $_POST["filename"] ) && isset( $_POST["size"] ) ) {

    if( !is_numeric( $_POST["size"] ) || $_POST["size"] * 1 > MaxUploadSize )
      die( json_encode( array( "Error"=>"エラー: ファイルサイズが" . MaxUploadSize . "バイトを超えています。(ERR_FILE_TOO_BIG)" ), JSON_UNESCAPED_UNICODE ) );
    
    $FileID = substr( md5( $_POST["filename"]. $_POST["size"] ), 0, 4 ) . dechex(time());
    $RemovePassword = GetRand( 8 );

    if ( file_exists( "{$basepath}{$FileID}" ) )
      die( json_encode( array( "Error"=>"エラー: このファイルは他の方によって既にアップロードされています。(ERR_FILE_EXISTS)" ), JSON_UNESCAPED_UNICODE ) );

    // $Hashは現段階では不明であり、これを残留チャンク数として使用する
    $Hash = ceil( $_POST["size"] / (80 * 1024 * 1024) ) ;

    $DownloadLimit = "0";
    if ( isset( $_POST["setLimitDownload"] ) && isset( $_POST["maxDownloadCount"] ) ) {

      if ( !is_numeric( $_POST["maxDownloadCount"] ) || $_POST["maxDownloadCount"] * 1 < 0 || !preg_match( "/^[0-9]+$/", $_POST["maxDownloadCount"] * 1 ) )
        die( json_encode( array( "Error"=>"エラー: 最大ダウンロード回数には、非負整数のみ指定できます。" ), JSON_UNESCAPED_UNICODE ) );

      $DownloadLimit = $_POST["maxDownloadCount"];

    }

    $BlockVPN = "false";
    if ( isset( $_POST["blockVPN"] ) ) {
        $BlockVPN = "true";
    }

    $IsEncrypted = "false";

    $DateLimit = "";
    if ( isset( $_POST["setDateLimit"] ) && isset( $_POST["DownloadLimit"] ) ) {
        $DateLimit = @strtotime( $_POST["DownloadLimit"] );
    }

    $FileName = $_POST["filename"];

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
        $_POST["size"],
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
            "",
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
                "ChunksAvailable" => $Hash,
                "RemovePassword" => $RemovePassword
            )
        )
    );

  }

  die( json_encode( array( "Error"=>"エラー: アップロードに失敗しました。(ERR_FILE_NOTSELECTED)" ), JSON_UNESCAPED_UNICODE ) );
