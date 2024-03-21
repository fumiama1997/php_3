<?php
$host   = 'localhost';
$user   = 'root';
$passwd = 'narait';
$dbname = 'drink';
$name = '';
$money = 0;
$stock = '';

if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    mysqli_set_charset($link, 'UTF8');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //information（drink_id 商品価格）の情報がpostされてくる。
        if ((isset($_POST['information'])) === false) {
            $error[] = '商品を選択してください';
        } else if (isset($_POST['information'])) {
            // 二つのValue値を分ける処理
            $information = explode(" ", $_POST['information']);
            $drink_id = $information[0];
            $price = $information[1];

            if ($drink_id === '') {
                $error[] = 'drink_idが入力されていません';
            } else if (is_numeric($drink_id) === false) {
                $error[] = 'drink_idが不正です';
            }

            if ($price === '') {
                $error[] = '商品の価格が空です';
            } else if ((is_numeric($price)) === false) {
                $error[] = '商品の価格が不正です';
            }
        }
        //投入金額
        if (isset($_POST['money'])) {
            $money = $_POST['money'];

            if ($money === '') {
                $error[] = 'お金を投入してください';
            } else if ((is_numeric($money)) === false) {
                $error[] = 'お金は半角数字を入力してください';
            } else if ((isset($price)) && ((intval($money)) < (intval($price)))) {
                $error[] = 'お金が足りません！';
            }
        }

        // 結果表示ページに必要な情報を取得する
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
                    //おつり
                    $money = $money - $price;

                    if ((intval($stock) === 0)) {
                        $error[] = '在庫がありません';
                    } else if ($status === '0') {
                        $error[] = 'ステータスが非公開の為購入できません';
                    } else {
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
        <p>おつりは[<?php print $money; ?>円]です</p>

    <?php } else { ?>
        <?php foreach ($error as $value) { ?>
            <p><?php print $value; ?></p>
        <?php } ?>

    <?php } ?>
    <a href="index.php">戻る</a>
</body>

</html>