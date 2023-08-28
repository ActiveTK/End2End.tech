<?php

  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

  if ( isset( $_GET["id"] ) && is_string( $_GET["id"] ) ) {

    $Note = array();
    $dbh = new PDO( DSN, DB_USER, DB_PASS );
    try {

      $stmt = $dbh->prepare( 'select * from UploadFiles where FileID = ?;' );
      $stmt->execute( [$_GET["id"]] );
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

      if ( !empty( FileInfo["FileDownloadLimit"] ) && FileInfo["FileDownloadLimit"] * 1 != 0 ) {
        if ( FileInfo["FileDownloadLimit"] * 1 <= FileInfo["DownloadCount"] * 1 )
          die( "アップロードしたユーザーが設定したファイルのダウンロード制限回数を超えたため、ファイルは無効となりました。" );
      }
      if ( !empty( FileInfo["FileValidDateLimit"] ) ) {
        if ( time() > FileInfo["FileValidDateLimit"] * 1 )
          die( "アップロードしたユーザーが設定したファイルのダウンロード期限を超えたため、ファイルは無効となりました。" );
      }

      if ( !empty( FileInfo["BlockVPN"] ) && FileInfo["BlockVPN"] == "true" ) {
        $headers = getallheaders();
        if ( !isset( $headers["Cf-Ipcountry"] ) || $headers["Cf-Ipcountry"] != "JP" )
          die( "アップロードしたユーザーの設定により、VPN経由のダウンロードは禁止されています。" );
        $IP = isset( $headers["Cf-Connecting-Ip"] ) ? $headers["Cf-Connecting-Ip"] : $_SERVER["REMOTE_ADDR"];
        $ipoc = explode(".", $IP);
        if ( @gethostbyname( $ipoc[3] . "." . $ipoc[2] . "." . $ipoc[1] . "." . $ipoc[0] . ".dnsel.torproject.org" ) == "127.0.0.2" )
          die( "アップロードしたユーザーの設定により、Tor経由のダウンロードは禁止されています。" );
      }

      $Note["DownloadCount"] = $Note["DownloadCount"] + 1;

      $stmt = $dbh->prepare("update UploadFiles set DownloadCount = ? where FileID = ?;");
      $stmt->execute([
        $Note["DownloadCount"],
        $Note["FileID"]
      ]);

    } catch (\Throwable $e) {
      die("SQLエラーが発生しました。");
    }

    header( "X-Content-Type-Options: nosniff" );
    header( "Content-Length: " . FileInfo["FileSize"] );
    header( "Content-Type: application/force-download" );
    header( "Content-Disposition: attachment; filename=\"" . urlencode( basename( FileInfo["FileName"] ) ) . "\"" );
    header( "Connection: close" );
    while ( ob_get_level() )
      ob_end_clean();

	exit( gzinflate( file_get_contents( $basepath . FileInfo["FileID"] ) ) );
  }

  header( "HTTP/1.1 404 Not Found" );
  die( "HTTP 404 - Not Found" );
