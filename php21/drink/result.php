<?php
$host   = 'localhost';
$user   = 'root';
$passwd = 'narait';
$dbname = 'drink';
$name = '';
$stock = '';

if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    mysqli_set_charset($link, 'UTF8');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['information'])) {

            if (empty($_POST['money'])) {
                $error[] = 'お金を投入してください';
            } else if ((is_numeric($_POST['money'])) === false) {
                $error[] = 'お金は半角数字を入力してください';
            }

            if (isset($_POST['drink_id']) === false) {
                $error[] = '商品を選択してください';
            } else {
                $drink_id = $_POST['drink_id'];
                //drink_idのバリデーション
                if ($drink_id === '') {
                    $error[] = 'drink_idが空です';
                } else if (is_numeric($drink_id) === false) {
                    $error[] = 'drink_idが不正です';
                }
                //$_POST['drink_id']がセットされている前提で下記処理が行われていく。
                if (empty($error)) {
                    $query = 'SELECT it.status,it.picture,it.name,it.price,st.stock FROM information_table AS it JOIN stock_table AS st ON  it.drink_id = st.drink_id WHERE it.drink_id = ' . $drink_id . '';
                    if (($result = mysqli_query($link, $query)) === false) {
                        $error[] = 'SQL失敗:';
                    } else {
                        $row = mysqli_fetch_assoc($result);
                        mysqli_free_result($result);
                        if (isset($row)) {
                            $picture = $row['picture'];
                            $name = $row['name'];
                            $price = $row['price'];
                            $stock = $row['stock'];
                            $status = $row['status'];

                            //在庫数のバリデーション
                            if ((intval($stock) === 0)) {
                                $error[] = '在庫がありません';
                            }

                            //公開ステ―タスのバリデーション
                            if ($status === '0') {
                                $error[] = 'ステータスが非公開の為購入できません';
                            }

                            //投入額よりも商品の価格が高くないか
                            if ((intval($_POST['money'])) < (intval($price))) {
                                $error[] = 'お金が足りません！';
                            } else {
                                $change = $_POST['money'] - $price;
                            }
                        }
                    }
                }

                // 結果表示ページに必要な情報を取得する
                if (empty($error)) {
                    $query = 'UPDATE stock_table SET stock = stock - 1 WHERE drink_id = ' . $drink_id . ' ';
                    if (($result = mysqli_query($link, $query)) === false) {
                        $error[] = 'SQL失敗:';
                        mysqli_close($link);
                    }
                }
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果ページ</title>
</head>

<body>
    <h1>自動販売機結果</h1>

    <?php if ((empty($error))) { ?>
        <img src="../../picture/<?php print htmlspecialchars($picture, ENT_QUOTES, 'UTF-8'); ?>">
        <p>がしゃん！[<?php print $name; ?>]が買えました！</p>
        <p>おつりは[<?php print $change; ?>円]です</p>

    <?php } else { ?>
        <?php foreach ($error as $value) { ?>
            <p><?php print $value; ?></p>
        <?php } ?>

    <?php } ?>
    <a href="index.php">戻る</a>
</body>

</html>