<?php
require_once('./define.php'); 
session_start();

if (!isset($_SESSION['sessionId'])) {
    header('Location: logout.php');
    exit;
}

$userId = $_SESSION['userId'];

$db['host'] = '127.0.0.1';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'test';

$mysql = mysqli_connect($db['host'], $db['user'], $db['pass']);
if (!$mysql) {
    die('接続失敗'.mysqli_error($mysql));
}

$db_selected = mysqli_select_db($mysql, $db['dbname']);
if (!$db_selected) {
    die ('データベース選択エラー'.mysqli_error($mysql));
}
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
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (!isset($_POST['newName']) || !strlen($_POST['newName'])) {
                        $error = '<div class="error">名前を入力してください。</div>';
                        echo ($error);
                    }
                    else if (mb_strlen($_POST['newName']) > 64) {
                        $error = '<div class="error">名前は64文字以内で入力してください。</div>';
                        echo $error;
                    }
                    else {
                        $newName = $_POST['newName'];
                        $result = mysqli_query($mysql, "UPDATE userData SET userName = '$newName' WHERE userId = '$userId'");
                        if (!$result) {
                            die ('名前の変更に失敗しました'.mysqli_error($mysql));
                        }
                        else {
                            $_SESSION['name'] = $newName;
                            header('Location: ./setting.php');
                        }
                    }
                }
            ?>
            <form class="changeNameForm" action="" method="post">
                <input class="changeNameForm" id="newName" type="text" name="newName" />
                <input class="changeNameForm" id="newNameSubmit" type="submit" name="newNameSubmit" />
            </form>
        </div>
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>