<?php

  header( "Content-Type: application/json" );
  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

  if( isset( $_POST["RawData"], $_POST["FileID"] ) ) {

    $Size = strlen( $_POST["RawData"] );
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

    $NonComData = gzinflate( file_get_contents( "{$basepath}{$FileID}" ) ) . $_POST["RawData"];
    file_put_contents(
        "{$basepath}{$FileID}",
        gzdeflate(
            $NonComData,
            9
        )
    );

    $ChunksAvailable = FileInfo["FileHash"] * 1 - 1;
    if ( $ChunksAvailable == 0 ) {
      FileInfo["FileHash"] = hash( "sha256", $NonComData );
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
