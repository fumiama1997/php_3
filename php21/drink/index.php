<?php
date_default_timezone_set('Asia/Tokyo');

// MySQL接続情報
$host   = 'localhost'; // データベースのホスト名又はIPアドレス
$user   = 'root';  // MySQLのユーザ名
$passwd = 'narait';    // MySQLのパスワード
$dbname = 'drink';    // データベース名

// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');

    $query =  'SELECT it.drink_id,it.picture,it.name,it.price,it.status,st.stock From information_table AS it JOIN stock_table AS st ON it.drink_id = st.drink_id';
    $result = mysqli_query($link, $query);

    // データを配列に入れる。
    while ($row = mysqli_fetch_array($result)) {
        $goods_data[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入フォーム</title>
    <style>
        div {
            text-align: center;
        }

        .row {
            width: 500px;
            display: flex;
            flex-wrap: wrap;
        }

        .child {
            width: calc(100%/4);
        }

        img {
            width: 100px;
            height: 120px;
        }

        .red {
            color: red;
        }

        p {
            margin: 0px;
        }
    </style>
</head>

<body>
    <h1>自動販売機</h1>
    <form method="post" action="./result.php">
        <p>金額 <input type="text" name= "money"></p>
        <div class="row">
            <?php foreach ($goods_data as $value) {  ?>
                <?php if ($value['status'] === '1') { ?>
                    <div class="child">

                        <div>
                            <img src="../../picture/<?php print htmlspecialchars($value['picture'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div>
                            <?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div>
                            <?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?>

                        </div>

                        <?php if (($value['stock']) === '0') {  ?>
                            <div>
                                <p class="red">売り切れ</p>
                            </div>
                        <?php } else { ?>
                            <div>
                                <input type="radio" name="drink_id" value='<?php print $value['drink_id']; ?>'>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <input type="submit" name="information" value="■□■□■購入■□■□■">
    </form>

</body>

</html>