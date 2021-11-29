<?php
require_once('./define.php'); 
session_start();

if (isset($_SESSION['sessionId'])) {
    $error = 'ログアウトしました。';
}
else {
    $error = '<div class="error">セッションがタイムアウトしました。</div>';
}

$_SESSION = array();
?>

<!DOCTYPE html>
<html>
    <head>
        <?php set_html_head(); ?>
    </head>

    <body>
        <div class="header"></div>
        <div class="main">
            <?php 
                echo $error;
            ?>
            <br/>
            <a href="./login.php">ログインページへ</a>
        </div>
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>