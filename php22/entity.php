<?php
$h1 = '「<h1>見出し</h1>」は見出しを表します。';
$p = '「<p>段落</p>」は段落を表します。';
$li = '「<li>リスト項目<li>」はリスト項目を表します。';

//特殊文字をHTMLエンティティに変換
$h1 = entity($h1);
$p = entity($p);
$li = entity($li);

function entity($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTMLエンティティ</title>
</head>
<body>
    <p><?php print $h1; ?></p>
    <p><?php print $p; ?></p>
    <p><?php print $li; ?></p>
</body>
</html>