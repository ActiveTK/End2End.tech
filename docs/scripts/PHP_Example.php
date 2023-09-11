<?php

  /!* ファイルのアップロード処理 */
  $UploadFile = "./Sample.jpg";
  $FileName = basename( $UploadFile );

  $file = fopen( $UploadFile, "rb" );

  $curl = curl_init();
  curl_setopt_array( $curl, [
    CURLOPT_UPLOAD => true,
    CURLOPT_URL => "https://api.end2end.tech/upload",
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => [ "Content-Type: image/jpg" ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_BINARYTRANSFER => true,
    CURLOPT_INFILE => $file,
    CURLOPT_INFILESIZE => filesize( $UploadFile )
  ] );
 
  $response = curl_exec( $curl );
  curl_close( $curl );

  $result = json_decode( $response, true );

  if ( !$result || isset( $result["Error"] ) )
    die ( ( isset( $result["Error"] ) ? "ファイルアップロード時にエラーが発生しました。" : $result["Error"] ) . "<br>" );
  else
    echo "ファイルID: " . $result["FileID"] . ", 削除パスワード: " . $result["RemovePassword"] . "<br>";

  /!* ファイルの取得 */
  $FileID = $result["FileID"];
  $Data = file_get_contents( "https://api.end2end.tech/download?id=" . urlencode( $FileID ) );
  echo "ダウンロードしたファイルのサイズ: " . strlen( $Data ) . "B<br>";

  /!* ファイルの削除 */
  $RemoveFile = $result["FileID"];
  $RemovePass = $result["RemovePassword"];
  $fetchResult = json_decode( file_get_contents( "https://api.end2end.tech/delete?id=" . urlencode( $RemoveFile ) . "&password=" . urlencode( $RemovePass ) ), true );
  echo ( isset( $fetchResult["Error"] ) ? $fetchResult["Error"] : "ファイルを削除しました。" ) . "<br>";
  
