<?php
require_once('./define.php'); 
session_start();
$db['host'] = '127.0.0.1';
$db['user'] = 'root';
$db['pass'] = '';
$db['dbname'] = 'test';
?>

<!DOCTYPE html>
<html>
<head>
        <?php set_html_head(); ?>
    </head>

<body>
    <div class="header">
    </div>
    <div class="main">
        <div class="loginForm">
            <div class="title">ログイン</div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['loginId']) || !strlen($_POST['loginId'])) {
                    $error = '<div class="error">名前を入力してください。</div>';
                    echo ($error);
                }

                if (!isset($_POST['loginPass']) || !strlen($_POST['loginPass'])){
                    $error = '<div class="error">パスワードを入力してください。</div>';
                    echo $error;
                }

                if (!empty($_POST['loginId']) && !empty($_POST['loginPass'])){
                    $userId = $_POST['loginId'];
                    
                    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

                    try {
                        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

                        $stmt = $pdo->prepare('SELECT * FROM userData WHERE userId = ?');
                        $stmt->execute(array($userId));

                        $userPass = $_POST['loginPass'];

                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            if (password_verify($userPass, $row['userPass'])) {
                                session_regenerate_id(true);

                                $sql = "SELECT * FROM userData WHERE userId = $userId";
                                $stmt = $pdo->query($sql);
                                foreach ($stmt as $row) {
                                    $row['userName'];
                                }
                                $_SESSION['userId'] = $row['userId'];
                                $_SESSION['name'] = $row['userName'];
                                $_SESSION['sessionId'] = hash('sha256', $row['userId'].$row['userName'].$userPass);
                                header('Location: index.php');
                                exit();
                            }
                            else {
                                $error = '<div class="error">ユーザIDまたはパスワードが間違っています。</div>';
                                echo $error;
                            }
                        }
                        else {
                            $error = '<div class="error">ユーザIDまたはパスワードが間違っています。</div>';
                            echo $error;
                        }
                    }
                    catch (PDOException $e){
                        $error = 'データベースエラー';
                        //$e->getMassage = $sql;
                        //echo $e->getMessage();
                    }
                }
            }
            else {
                $error = '送信形式がPOSTではありません';
            }

            ?>
            <form action="" method="post">
                <div class="item">ユーザID</div>
                <input type="text" id="loginId" name="loginId" /><br />
                <div class="item">パスワード</div>
                <input type="password" id="loginPass" name="loginPass" /><br />
                <input type="submit" id="loginSubmit" name="loginSubmit" />
            </form>
        </div>
        <a href="./signup.php">新規登録</a>
    </div>
    <div class="footer">
        <?php set_footer(); ?>
    </div>
</body>

</html>