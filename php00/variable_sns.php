<?php
$name = '山田 太郎';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>変数の使用例</title>
</head>

<body>
    <h1>変数の使用例</h1>
    <p>ようこそ<?php print $name; ?>さん。</p>
    <ul>
        <li><a href="#"><?php print $name; ?>さんのマイページを見る</a></li>
    </ul>
</body>
</html>