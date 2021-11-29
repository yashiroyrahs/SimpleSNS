<?php
require_once('./define.php'); 
session_start();
$db['host'] = '127.0.0.1';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'test';

$error = '';
?>

<!doctype html>
<html>
    <head>
        <?php set_html_head(); ?>
    </head>

    <body>
        <div class="header"></div>
        <div class="main">
            <div class="title">新規登録</div>
            <?php
                if (isset($_POST['signupSubmit'])) {
                    if (empty($_POST['userName'])) {
                        $error = '<div class="error">名前が入力されていません。</div>';
                        echo $error;
                    }
                    else if (mb_strlen($_POST['userName']) > 64) {
                        $error = '<div class="error">名前は64文字以内で入力してください。</div><br />';
                        echo $error;
                    }

                    if (empty($_POST['password'])) {
                        $error = '<div class="error">パスワードが未入力です。</div>';
                        echo $error;
                    }
                    else if (strlen($_POST['password']) < 8) {
                        $error = '<div class="error">パスワードは8文字以上で入力してください。</div>';
                        echo $error;
                    }
                    else if (strlen($_POST['password']) > 64) {
                        $error = '<div class="error">パスワードは64文字未満で入力してください。</div>';
                        echo $error;
                    }

                    if (empty($_POST['password2'])){
                        $error = '<div class="error">パスワードを再度入力してください。</div>';
                        echo $error;
                    }

                    if (!empty($_POST['userName']) && !empty($_POST['password']) && !empty($_POST['password2'])) {
                        if ($error === '') {
                            if ($_POST['password'] == $_POST['password2']) {
                                $userName = $_POST['userName'];
                                $password = $_POST['password'];
                                $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

                                try {
                                    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
                                    $stmt = $pdo->prepare('INSERT INTO userData(userName, userPass) VALUES (?, ?)');
                                    $stmt->execute(array($userName, password_hash($password, PASSWORD_DEFAULT)));
                                    $userId = $pdo->lastinsertid();

                                    $_SESSION['name'] = $userName;
                                    $_SESSION['userId'] = $userId;
                                    $_SESSION['sessionId'] = hash('sha256', $userId.$userName.$password);

                                    header('Location: ./welcome.php');
                                }
                                catch (PDOException $e){
                                    $error = 'データベースエラー';
                                }
                            }
                            else {
                                $error = '<div class="error">パスワードが一致していません</div>';
                                echo $error;
                            }
                        }
                    }
                }

                ?>
            <form id="signupForm" name="signupForm" action="" method="POST">
                <div class="item">名前</div>
                <input type="text" id="userName" name="userName" /><br />
                <div class="item">パスワード</div>
                <input type="password" id="password" name="password" /><br />
                <div class="item">パスワード(再入力)</div>
                <input type="password" id="password2" name="password2" /><br />
                <input type="submit" id="signupSubmit" name="signupSubmit" />
            </form>
            <br>
            <form action="Login.php">
                <input type="submit" value="戻る">
            </form>
        </div>
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>