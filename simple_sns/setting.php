<?php
    require_once('./define.php'); 
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
            <div class="title">設定</div>
            <div class="setting_box" id="setting_box">
                <a href="./changeName.php">名前の変更</a><br />
                <a href="./changePass.php">パスワードの変更</a>
            </div>
        </div>
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>