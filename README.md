# Simple SNS
## 概要：
基本的な機能のみを備えたシンプルなSNSのWebサイトです。

## 開発経緯：
とあるVTuberのファンコミュニティサイトを立ち上げたいと思ったのがきっかけです。

個人で他者のメールアドレスを管理することはリスクが高いため、ユーザ登録にはメールアドレスを用いずに、あらかじめ定めたIDを配布する実装としました。このため、管理者・利用者双方で安全性が高められ、且つ「任意のIDに変更可能」な実装でやらなければならない重複チェックを回避できるというメリットを得られました。

しかし、個人で継続的にサイトを運営することが困難に思えたことと、実装がセキュアである保証ができなかったため、実際に運用することはせず、1年ほど眠らせていたプログラムを修正・改良し、開発経験の1つとしてGitHubに公開するに至りました。

## 仕様：
ユーザはサービスを使うにあたってユーザ登録を行う必要がある。

登録には名前およびパスワードの設定が必要であり、登録が完了するとIDが配布される(IDはデータベースのAIによって整数値が与えられる。変更は不可)。

ログイン後はindex.phpに遷移しタイムラインが表示される。

投稿を行うには「投稿する」ボタンをクリックし、投稿用のページへ移動する必要がある(本文未入力のまま投稿を行うことはできない。また、画像を投稿する場合にはサイズは長辺が500px以内でなければならず、画像を添付した場合でも本文にはなんらかの文字を入力する必要がある)。

名前およびパスワードは専用のページで変更できる。ただし、IDは変更不可。

## 機能一覧：
* ユーザ登録
* ログイン・ログアウト機能
* つぶやき投稿
* 画像の投稿
* 名前の変更
* パスワードの変更

## 実装環境：
### -使用言語-
* PHP
* HTML
* CSS

### -データベース-
* MySQL
    * データベース名 test
    * テーブル
        * userData(userId(AI), userName, userPass)
        * post(userId, Pname, maintext, created_day, imageName)