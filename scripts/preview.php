<?php

  if ( !defined( "FileInfo" ) ) {
    header( "HTTP/1.1 500 Internal Server Error" );
    die( "内部エラーが発生しました。" );
  }
  
  $basepath = "../objects/blob/";

  if ( !defined( "DownloadBlocked" ) && !defined( "EndtoEndEncrypted" ) ) {

    if ( !empty( FileInfo["EndtoEndEncrypted"] ) && FileInfo["EndtoEndEncrypted"] == "true" ) {

      $tempData = sys_get_temp_dir() . "/end2endtech_" . FileInfo["FileID"];
      if ( $FileSize > 1024 ) {
        file_put_contents( $tempData, substr( gzinflate( file_get_contents( $basepath . FileInfo["FileID"] ) ) , 0, 1024 ) . ".. (省略されました)" );
      } else {
        file_put_contents( $tempData, gzinflate( file_get_contents( $basepath . FileInfo["FileID"] ) ) );
      }

      echo "<iframe style='width:604px;height:340px;' src='" . APIEndPoint . "preview" . ( EnableAPIAsSubDomain ? "?" : "&" ) . "id=" . FileInfo["FileID"] . "&encrypted'></iframe><br>";

    } else {

      $PathInfo = pathinfo( FileInfo["FileName"] );
      if ( !isset( $PathInfo["extension"] ) )
        $PathInfo["extension"] = "";

      $Ext = strtolower( $PathInfo["extension"] );

      if ( $Ext == "png" || $Ext == "jpeg" || $Ext == "jpg" || $Ext == "gif" ) {

        if ( $Ext == "jpeg" )
          $Ext = "jpg";

        if ( FileInfo["FileSize"] < 1024 * 1024 * 10 ) {

          $tempImage = sys_get_temp_dir() . "/end2endtech_" . FileInfo["FileID"];
          if ( !file_exists( $tempImage ) ) {
            $def = gzinflate( file_get_contents( $basepath . FileInfo["FileID"] ) );
            file_put_contents( $tempImage, $def );
            $size = getimagesizefromstring( $def );
          } else {
            $size = getimagesize( $tempImage );
          }

          if ( $size ) {

            list($width, $height, $type, $attr) = $size;

            if( $width >= 800 || $height >= 800 ) {

              if ( $type == IMAGETYPE_JPEG ) {
                $original_image = imagecreatefromjpeg( $tempImage );
                $newW = 600;
                $newH = $newW * ($height / $width);
                $newImg = imagecreatetruecolor( $newW, $newH );
                $success = imagecopyresampled( $newImg, $original_image, 0, 0, 0, 0, $newW, $newH, $width, $height );
                if ( $success ) {
                  imagejpeg( $newImg, $tempImage, 60 );
                  $width = $newW;
                  $height = $newH;
                }
              }
              else if ( $type == IMAGETYPE_PNG ) {
                $original_image = imagecreatefrompng( $tempImage );
                $newW = 600;
                $newH = $newW * ($height / $width);
                $newImg = imagecreatetruecolor( $newW, $newH );
                $success = imagecopyresampled( $newImg, $original_image, 0, 0, 0, 0, $newW, $newH, $width, $height );
                if ( $success ) {
                  imagepng( $newImg, $tempImage, 6 );
                  $width = $newW;
                  $height = $newH;
                }

              }

            }

            echo "<div><img src='" . APIEndPoint . "preview" . ( EnableAPIAsSubDomain ? "?" : "&" ) . "id=" . FileInfo["FileID"] . "&type=" . $Ext . "' style='height:400px;width:auto;'></div><br>";

          }

        }

      } else if (
          $Ext == "txt" || $Ext == "sql" || $Ext == "php" || $Ext == "html" ||
          $Ext == "cgi" || $Ext == "cs" || $Ext == "c" || $Ext == "py" ||
          $Ext == "js" || $Ext == "json" || $Ext == "css" || $Ext == "csv" ||
          $Ext == "rs" || $Ext == "cpp" ) {

        $Ext = "txt";

        $tempData = sys_get_temp_dir() . "/end2endtech_" . FileInfo["FileID"];
        if ( $FileSize > 1024 ) {
          file_put_contents( $tempData, substr( gzinflate( file_get_contents( $basepath . FileInfo["FileID"] ) ) , 0, 1024 ) . "\n\n(1025バイト以降は省略されました。続きはダウンロードしてご確認下さい。)" );
        } else {
          file_put_contents( $tempData, gzinflate( file_get_contents( $basepath . FileInfo["FileID"] ) ) );
        }

        echo "<iframe style='width:604px;height:340px;' src='" . APIEndPoint . "preview" . ( EnableAPIAsSubDomain ? "?" : "&" ) . "id=" . FileInfo["FileID"] . "&type=" . $Ext . "'></iframe><br>";

      }

    }

  }
