<?php
    function set_html_head(){
        echo('
        <title>Simple SNS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
        <meta http-equiv="Content-Style-Type" content="text/css" />

        <link href="./css/main.css" rel="stylesheet" type="text/css" />
        ');
    }

    function set_header(){
        echo('
        <div class="title">
            <a class="title" id="titleLink" href="./index.php">Simple SNS</a>
        </div>
        <div class="logout">
            <a class="logout" id="logoutLink" href="./logout.php">ログアウト</a>
        </div>
        <div class="setting">
            <a class="setting" id="settingLink" href="./setting.php">設定</a>
        </div>
        <div class="post">
            <a class="post" id="postLink" href="./post.php">投稿する</a>
        </div>
        ');
    }

    function set_footer(){
        echo('
        <p class="footer">© 2021 Simple SNS(yashiroyrahs)</p>
        ');
    }
?>