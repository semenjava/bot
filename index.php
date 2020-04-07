<?php
require_once 'simplehtmldom/simple_html_dom.php';
require_once 'Bot.php';

set_time_limit(0);

if(!empty($_POST['domen'])) {
    $bot = new Bot($_POST['domen']);
    $bot->generationFiles();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Бот</title>
        <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    </head>
    <body>
        <form method="post">
            <label>Введите домен</label>
            <input type="text" name="domen" >
            <input type="submit" id="submit">
        </form>
    </body>
</html>