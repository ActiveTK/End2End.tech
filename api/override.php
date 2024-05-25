<?php

  header( "Content-Type: application/json" );
  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

  if( is_uploaded_file($_FILES["RawData"]["tmp_name"]) && isset( $_POST["FileID"] ) ) {

    $Size = filesize( $_FILES["RawData"]["tmp_name"] );
    if( $Size > 80 * 1024 * 1024 + 1024 )
      die( json_encode( array( "Error"=>"エラー: ファイルサイズが" . (80 * 1024 * 1024 + 1024) . "バイトを超えています。(ERR_FILE_TOO_BIG)" ), JSON_UNESCAPED_UNICODE ) );
    
    try {

      $dbh = new PDO( DSN, DB_USER, DB_PASS );
      $stmt = $dbh->prepare( 'select * from UploadFiles where FileID = ?;' );
      $stmt->execute( [$_POST["FileID"]] );
      $resd = $stmt->fetch( PDO::FETCH_ASSOC );
      if ($resd !== false)
        $Note = $resd;
      else
      {
        header( "HTTP/1.1 404 Not Found" );
        die( "HTTP 404 - Not Found" );
      }
      unset($resd);

      define( "FileInfo", $Note );

    } catch (\Throwable $e) {
      die( json_encode( array( "Error"=>"エラー: アップロードの処理に失敗しました。(ERR_SQL_FAILED)" ), JSON_UNESCAPED_UNICODE ) );
    }

    if (strlen(FileInfo["FileHash"]) == 64) {
      die( json_encode( array( "Error"=>"エラー: 無効なファイルハッシュです。(INVAILED_FILEHASH)" ), JSON_UNESCAPED_UNICODE ) );
    }

    $ChunksAvailable = FileInfo["FileHash"] * 1 - 1;

    $a = fopen( "{$basepath}{$FileID}", "a" );
    @fwrite($a, file_get_contents($_FILES["RawData"]["tmp_name"]));
    fclose($a);

    $FileLen = filesize( "{$basepath}{$FileID}" );

    if ( $ChunksAvailable == 0 ) {

      file_put_contents(
        "{$basepath}{$FileID}",
        gzdeflate(
            file_get_contents( "{$basepath}{$FileID}" ),
            9
        )
      );
      $ChunksAvailable = hash( "sha256", $NonComData );

    }

    try {

      $dbh = new PDO( DSN, DB_USER, DB_PASS );
      $stmt = $dbh->prepare(
        "update UploadFiles set FileSize = ?, FileHash = ? where FileID = ?;"
      );
      $stmt->execute([
        $FileLen,
        $ChunksAvailable,
        $FileID
      ]);

    } catch (\Throwable $e) {
      die( json_encode( array( "Error"=>"エラー: アップロードの処理に失敗しました。(ERR_SQL_FAILED)" ), JSON_UNESCAPED_UNICODE ) );
    }

    exit(
        json_encode(
            array(
                "Status" => "OK",
                "ChunksAvailableOrFileHash" => $ChunksAvailable
            )
        )
    );

  }

  die( json_encode( array( "Error"=>"エラー: アップロードに失敗しました。(ERR_FILE_NOTSELECTED)" ), JSON_UNESCAPED_UNICODE ) );
