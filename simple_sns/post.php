<?php
require_once('./define.php'); 
session_start();

if (!isset($_SESSION['sessionId'])) {
    header('Location: logout.php');
    exit;
}

$db['host'] = '127.0.0.1';
$db['dbname'] = 'test';
$db['user'] = 'root';
$db['pass'] = '';
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
            <div class="title">投稿</div>
            <div class="post_form">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['maintext']) || !strlen($_POST['maintext'])) {
                    $error = '<div class="error">本文を入力してください。</div>';
                    echo ($error);
                }
                else if (mb_strlen($_POST['maintext'], 'utf-8') > 140) {
                    $error = '<div class="error">本文は140文字以内で入力してください。</div>';
                    echo ($error);
                }
                else {
                    $error = "";
                    $maintext = $_POST['maintext'];
                }

                $date = new DateTime();
                $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
                $date_data = $date->format('YmdHisu');
                if ($error === '') {
                    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $db['host'], $db['dbname']);
                    $pdo = new PDO($dsn, $db['user'], $db['pass']);

                    if(is_uploaded_file($_FILES['image']['tmp_name'])){
                        $file_tmp = $_FILES['image']['tmp_name'];
                        $file_type = substr(strrchr($_FILES['image']['name'], '.'), 1);
                        $hashName = hash('sha224', (string)$date_data);
                        $image_name = str_replace(array(" ", "　"), "", $hashName.'.'.$file_type);
                        $path = './images/'.$image_name;
                        list($width, $height) = getimagesize($file_tmp);
                        
                        if (!exif_imagetype($_FILES['image']['tmp_name'])) {
                            $error = '<div class="error">画像ファイルではありません。</div>';
                            echo ($error);
                        }
                        else if ($width > 500 || $height > 500) {
                            $error = '<div class="error">サイズが大きすぎます。長辺を500px以下にして再度アップロードをして下さい。</div>';
                            echo $error;
                        }
                        else {
                            move_uploaded_file($file_tmp, $path);
                            $stmt = $pdo->prepare('INSERT INTO `post` (userId, Pname, maintext, created_day, imageName) VALUES (:userId, :Pname, :maintext, :created_day, :imageName)');

                            $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
                            $stmt->bindParam(':Pname', $_SESSION['name'], PDO::PARAM_STR);
                            $stmt->bindParam(':maintext', $maintext, PDO::PARAM_STR);
                            $stmt->bindParam(':created_day', $date->format('Y-m-d H:i:s.u'), PDO::PARAM_STR);
                            $stmt->bindParam(':imageName', $image_name, PDO::PARAM_STR);
                            $stmt->execute();
                            header('Location: index.php');
                        }
                    }
                    else {
                        $stmt = $pdo->prepare('INSERT INTO `post` (userId, Pname, maintext, created_day) VALUES (:userId, :Pname, :maintext, :created_day)');

                        $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
                        $stmt->bindParam(':Pname', $_SESSION['name'], PDO::PARAM_STR);
                        $stmt->bindParam(':maintext', $maintext, PDO::PARAM_STR);
                        $stmt->bindParam(':created_day', $date->format('Y-m-d H:i:s.u'), PDO::PARAM_STR);
                        $stmt->execute();
                        header('Location: index.php');
                    }
                }
                else {
                    $error = '<div class="error">追加に失敗しました。</div>';
                    echo ($error);
                }
            }
            ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="item">本文(140文字以内)</div>
                    <textarea class="post_form" id="maintext" name="maintext" rows="8" cols="50"></textarea><br />
                    <div class="item">画像(長辺500px以下)</div>
                    <input class="post_form" id="image" name="image" type="file" />
                    <input class="post_form" id="post_submit" name="post_submit" type="submit" />
                </form>
            </div>
        </div>
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>



