<?php
require_once('./define.php'); 
    session_start();
    if (!isset($_SESSION['sessionId'])) {
        header('Location: ./logout.php');
        exit;
    }

    $userId = $_SESSION['userId'];
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php set_html_head(); ?>
    </head>
    <body>
        <div class="header">
            <?php set_header(); ?>
        </div>
        <div class="main">
        <?php
            echo '<p>';
            echo 'ご登録ありがとうございます。<br />';
            echo 'あなたのIDは <strong>'.$userId.'</strong> です。ログインに必要になりますので、忘れないよう大切に保管してください。';
            echo '<a href="./index.php">メインページへ</a>';
            echo '</p>';
        ?>
        </div>]
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>