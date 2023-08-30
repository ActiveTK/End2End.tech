<?php

  header( "X-Robots-Tag: noindex,nofollow,noarchive" );

  $basepath = "../../objects/blob/";

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
        $Mime = "plain/text";

	  default:
		die();
        break;
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
