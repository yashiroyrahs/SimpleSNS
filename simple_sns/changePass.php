<?php
require_once('./define.php'); 
session_start();

if (!isset($_SESSION['sessionId'])){
    header('Location: ./login.php');
    exit;
}

$db['host'] = '127.0.0.1';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'test';
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
                    if (!isset($_POST['oldPass']) || !strlen($_POST['oldPass'])) {
                        $error = '<div class="error">現在のパスワードを入力してください。</div>';
                        echo ($error);
                    }

                    if (!isset($_POST['newPass']) || !strlen($_POST['newPass'])) {
                        $error = '<div class="error">新しいパスワードを入力してください。</div>';
                        echo ($error);
                    }
                    else if (mb_strlen($_POST['newPass']) < 8) {
                        $error = '<div class="error">パスワードは8文字以上で入力してください。</div>';
                        echo $error;
                    }
                    else if (mb_strlen($_POST['newPass']) > 64) {
                        $error = '<div class="error">パスワードは64文字未満で入力してください。</div>';
                        echo $error;
                    }
                    if (!isset($_POST['newPass2']) || !strlen($_POST['newPass2'])) {
                        $error = '<div class="error">再度新しいパスワードを入力してください。</div>';
                    }

                    if (isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['newPass2'])) {
                        if ($_POST['newPass'] != $_POST['newPass2']) {
                            $error = '<div class="error">新しいパスワードが一致していません。</div>';
                            echo $error;
                        }

                        $userId = $_SESSION['userId'];

                        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

                        try {
                            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

                            $stmt = $pdo->prepare('SELECT * FROM userData WHERE userId = ?');
                            $stmt->execute(array($userId));

                            $oldPass = $_POST['oldPass'];

                            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                if (password_verify($oldPass, $row['userPass'])) {
                                    session_regenerate_id(true);

                                    $hashedPass = password_hash($_POST['newPass'], PASSWORD_DEFAULT);

                                    $sql = "UPDATE userData SET userPass = '$hashedPass' WHERE userId = '$userId'";
                                    $stmt = $pdo->query($sql);

                                    $_SESSION['userId'] = $row['userId'];
                                    $_SESSION['name'] = $row['userName'];
                                    $_SESSION['sessionId'] = hash('sha256', $row['userId'].$row['userName'].$_POST['newPass']);
                                    header('Location: ./setting.php');
                                    exit();
                                }
                                else {
                                    $error = '<div class="error">現在のパスワードが間違っています。</div>';
                                    echo $error;
                                }
                            }
                        }
                        catch (PDOException $e){
                            $error = 'データベースエラー';
                            //$e->getMassage = $sql;
                            //echo $e->getMessage();
                        }
                    }
                }
            ?>
            <form class="changePassForm" action="" method="post">
                <div class="item">現在のパスワード</div>
                <input class="changePassForm" id="oldPass" type="password" name="oldPass" />
                <div class="item">新しいパスワード</div>
                <input class="changePassForm" id="newPass" type="password" name="newPass" />
                <div class="item">新しいパスワード(再入力)</div>
                <input class="changePassForm" id="newPass2" type="password" name="newPass2" /><br />
                <input class="changePassForm" id="newPAssSubmit" type="submit" name="newPassSubmit" />
            </form>
        </div>
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>