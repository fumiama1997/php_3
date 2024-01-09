<?php
$hp = 100;




?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>演算子の例</title>
</head>
<body>
    <h2>初期のHP: <?php print $hp; ?></h2>
    <p>攻撃</p>
    <?php
    $damege = mt_rand(1,100);
    $hp = $hp-$damege;
    ?>
    <p><?php print $damege ?>のダメージ</p>
    <p>残りHP: <?php print $hp; ?></p>
    <p>追撃</p>
    <?php
    $damege = mt_rand(1,80);
    $hp = $hp - $damege;
    ?>
    <p><?php print $damege ?>のダメージ</p>
    <p>残りHP: <?php print $hp; ?></p>
</body>
</html>
