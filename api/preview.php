<?php

  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

  if ( isset( $_GET["id"] ) && is_string( $_GET["id"] ) &&
       isset( $_GET["encrypted"] ) && is_string( $_GET["encrypted"] ) ) {

    $_GET["id"] = basename( $_GET["id"] );

    if ( !ctype_alnum( $_GET["id"] ) )
      die();

    $File = sys_get_temp_dir() . "/end2endtech_" . $_GET["id"];
    if ( !file_exists( $File ) )
      die();

    header( "Content-Type: text/html" );
    echo "<html><head><meta name=\"robots\" content=\"noindex, nofollow\"><meta name=\"color-scheme\" content=\"dark light\"></head><body style=\"background-color:#181818;color:#ffffff;\"><pre style=\"word-wrap: break-word; white-space: pre-wrap;\">";
    $fileheader = file_get_contents( $File, FALSE, NULL, 0, 1024 );
    $text = json_encode( array(
      "Encrypted" => true,
      "Salt" => substr($fileheader, 0, 32),
      "IV" => substr($fileheader, 33, 65),
      "Mode" => "CryptoJS.mode.CBC",
      "Data" => substr($fileheader, 66) . "...",
    ), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT );
    echo htmlspecialchars( $text ), ENT_SUBSTITUTE, 'UTF-8' );
    echo "</pre></body></html>";
    exit();
  }

  if ( isset( $_GET["id"] ) && is_string( $_GET["id"] ) &&
       isset( $_GET["type"] ) && is_string( $_GET["type"] ) ) {

    $_GET["id"] = basename( $_GET["id"] );

    if ( !ctype_alnum( $_GET["id"] ) )
      die();

    $File = sys_get_temp_dir() . "/end2endtech_" . $_GET["id"];
    if ( !file_exists( $File ) )
      die();

    switch ( $_GET["type"] ) {
      case 'jpg':
        $Mime = "image/jpg";
		break;
	
      case 'png':
        $Mime = "image/png";
        break;

      case 'gif':
        $Mime = "image/gif";
        break;

      case 'txt':
        $Mime = "text/plain";
        break;

	  default:
		die();
        break;
    }

    if ( $Mime == "text/plain" ) {
      header( "Content-Type: text/html" );
      echo "<html><head><meta name=\"robots\" content=\"noindex, nofollow\"><meta name=\"color-scheme\" content=\"dark light\"></head><body style=\"background-color:#181818;color:#ffffff;\"><pre style=\"word-wrap: break-word; white-space: pre-wrap;\">";
      echo htmlspecialchars( file_get_contents( $File ), ENT_SUBSTITUTE, 'UTF-8' );
      echo "</pre></body></html>";
      exit();
    }

    header( "X-Content-Type-Options: nosniff" );
    header( "Content-Type: " . $Mime );
    header( "Content-Length:" . filesize( $File ) );
    header( "Connection: close" );
    while ( ob_get_level() )
      ob_end_clean();

    readfile( $File );
	exit();

  }

  header( "HTTP/1.1 404 Not Found" );
  die( "HTTP 404 - Not Found" );
