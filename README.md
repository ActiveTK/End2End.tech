# End2End.tech

<img src="https://cdn.jsdelivr.net/gh/ActiveTK/End2End.tech@main/objects/images/end2endtech.png" style="width:auto;height:120px;">

End2End暗号化対応・ノーログで、安心安全のオープンソースの匿名ファイルアップロードサービス。

## URL
<a href="https://end2end.tech/" target="_blank">https://end2end.tech/</a>

## Features
<li>1ファイル100MBまでアップロード可能</li>
<li>パスワードによるEnd to End暗号化</li>
<li>ハッシュ値の表示</li>
<li>ダウンロード回数制限</li>
<li>ファイルの有効期限設定</li>
<li>VPN経由のダウンロードの許可設定</li>
<li>ファイル削除機能</li>
<li>APIによるファイルアップロード</li>

## API

End2End.techでは、外部のスクリプトやコマンドラインから簡単にファイルをアップロードできるAPIを用意しています。 これらのAPIの利用に登録は必要ありませんが、極端にサーバーへ負荷をかける行為やスクレイピングなどはお止めください。

### ファイルの新規アップロード

ファイルを新規にアップロードするには、以下のURLにPOSTリクエストを送信して下さい。

```bash
curl https://api.end2end.tech/upload
  -X POST
  -F file=@/fakepath/helloworld.png
```

レスポンスは以下のようになります。

リクエストに成功した場合:

```json
{
  "Status": "OK",
  "FileID": "ファイルID",
  "FileName": "ファイル名",
  "URL": "ファイルのダウンロード用URL",
  "SHA256": "ファイルのSHA256ハッシュ",
  "RemovePassword": "ファイルの削除パスワード"
}
```

リクエストに失敗した場合:

```json
{
  "Error": "エラーの詳細メッセージ"
}
```

また、以下のようにリクエストにオプションを付属させることもできます。

```bash
curl https://api.end2end.tech/upload
  -X POST
  -F file=@/fakepath/helloworld.png
  -F setLimitDownload=on
  -F maxDownloadCount=100
  -F blockVPN=on
```

現在、対応しているオプションは以下の通りです。

<li>blockVPN: onに設定すると、VPNやTorを経由したファイルのダウンロードを拒否します。</li>
<li>setLimitDownload: onに設定すると、ファイルの最大ダウンロード回数を設定できます。maxDownloadCountと組み合わせて使用して下さい。</li>
<li>maxDownloadCount: ファイルの最大ダウンロード回数を数値で指定できます。利用には、setLimitDownloadが必須です。</li>
<li>setDateLimit: onに設定すると、ファイルのダウンロード期限を設定できます。DownloadLimitと組み合わせて使用して下さい。</li>
<li>DownloadLimit: ファイルのダウンロード期限を指定できます。strtotimeで処理できる形式で指定して下さい。利用には、DownloadLimitが必須です。</li>

### ファイルのダウンロード

ファイルをダウンロードするには、以下のURLにGETリクエストを送信して下さい。

```bash
curl https://api.end2end.tech/download?id={ファイルID} -o {出力先ファイル名}
```

ただし、{ファイルID}にはアップロード時のFileIDを指定し、{出力先ファイル名}の指定は任意です。

### ファイルの削除

ファイルを削除するには、以下のURLにGETリクエストを送信して下さい。

```bash
curl https://api.end2end.tech/delete?id={ファイルID}&password={削除用パスワード}
```

ただし、{ファイルID}にはアップロード時のFileIDを指定し、{削除用パスワード}にはアップロード時のRemovePasswordを指定して下さい。
この操作は取り消せず、ディスク及びデータベースから完全にファイルが消去されますので、注意して下さい。

## Setup

### 動作環境

Apache 2.4.6以降 + PHP 7.4.33以降 + MySQL 5.7以降

### Step1. リポジトリのクローン

以下のようにコマンドを実行して、リポジトリをクローンして下さい。

```bash
git clone https://github.com/ActiveTK/End2End.tech.git
```

または、ZIP形式で<a href="https://github.com/ActiveTK/End2End.tech/archive/refs/heads/main.zip" target="_blank">ダウンロード</a>してサーバー上に展開することもできます。

### Step2. データベースの用意

MySQLユーザーとデータベースを作成し、以下のようにテーブルを構築して下さい。

```sql
CREATE TABLE `UploadFiles` (
  `FileID` varchar(40) DEFAULT '',
  `FileName` varchar(500) DEFAULT '',
  `FileSize` varchar(20) DEFAULT '',
  `FileHash` varchar(400) DEFAULT '',
  `DownloadCount` varchar(20) DEFAULT '',
  `UploadDate` varchar(20) DEFAULT '',
  `FileDownloadLimit` varchar(20) DEFAULT '',
  `FileValidDateLimit` varchar(20) DEFAULT '',
  `EndtoEndEncrypted` varchar(5) DEFAULT '',
  `BlockVPN` varchar(5) DEFAULT '',
  `DeletePassword` varchar(8) DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
```

### Step3. Config.phpの編集

ルートディレクトリ直下にある`Config.php`をUTF-8としてテキストエディタで開き、「設定はここから」から「設定はここまで」の間を適切に編集して下さい。

設定項目は以下の通りです。

<li>Domain - サーバーのドメイン名です。</li>
<li>EnableSSL - trueを指定すると、通信にSSL/TLS暗号化を使用します。</li>
<li>NotificationEmail - 「お問い合わせ」の通知先メールアドレスです。空文字を指定すると、送信しません。</li>
<li>EnableAPIAsSubDomain - trueを指定すると、APIのエンドポイントがサブドメイン(api.example.com)となります。適切なDNSレコードの設定とApacheのバーチャルホスト設定が必要です。</li>
<li>MaxUploadSize - アップロードできるファイルの最大サイズを指定できます。</li>
<li>DEBUG - trueを指定すると、デバッグモードが有効となり、エラーの詳細情報が表示されます。</li>
<li>DB_Name - データベースの名前です。</li>
<li>DB_Host - データベースのホスト名です。</li>
<li>DB_Charset - データベースの文字コードです。</li>
<li>DB_USER - データベースのユーザー名です。</li>
<li>DB_PASS - データベースのパスワードです。</li>

## License
The MIT License
