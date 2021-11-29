<?php
require_once('./define.php'); 
session_start();
if (!isset($_SESSION['sessionId'])) {
    header('Location: logout.php');
    exit;
}

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

$result = mysqli_query($mysql, "SELECT * FROM post");
if (!$result) {
    die ('クエリ―が失敗しました'.mysqli_error($mysql));
}

$posts = array();
if ($result != false && mysqli_num_rows($result)) {
    while ($post = mysqli_fetch_assoc($result)) {
        $posts[] = $post;
    }
}

$posts = array_reverse($posts);

mysqli_free_result($result);
mysqli_close($mysql);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php set_html_head(); ?>
        <link href="./css/index.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="header">
        <?php set_header(); ?>
        </div>
        <div class="main">
            <div class="title">タイムライン</div>
        <?php
            if (count($posts) > 0) {
                echo '<div class="posted">'."\n";
                foreach ($posts as $post) {
                    echo '<div class="posted_box" id="posted_box">';
                    echo '<div class="posted_box" id="name">'.htmlspecialchars($post['Pname'],  ENT_QUOTES, 'UTF-8').'</div>'."\n";
                    echo '<div class="posted_box" id="id">ID: '.htmlspecialchars($post['userId'], ENT_QUOTES, 'UTF-8').'</div>'."\n";
                    echo '<div class="posted_box" id="date">'.htmlspecialchars($post['created_day'], ENT_QUOTES, 'UTF-8').'</div>'."\n";
                    echo '<div class="posted_box" id="maintext">'.htmlspecialchars($post['maintext'], ENT_QUOTES, 'UTF-8').'</div>'."\n";
                    if (strlen($post['imageName'])){
                        echo '<img class="posted_box" id="image" src="./images/'.$post['imageName'].'" />'."\n";
                    }
                    echo '</div>'."\n";
                }
                echo '</div>'."\n";
            }
        ?>
        </div> 
        <div class="footer">
            <?php set_footer(); ?>
        </div>
    </body>
</html>