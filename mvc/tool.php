<?php

require_once '../../include/conf/drink_const.php';

require_once '../../include/model/drink.php';

$error = [];
$drink_data = [];
$result = '';

// DB接続
$link = get_db_connect();
// リクエストメソッド取得
$request_method = get_request_method();
if ($request_method === 'POST') {
    if (isset($_POST['insert'])) {

        $name = get_post_data('name');
        $price = get_post_data('price');
        $piece = get_post_data('piece');
        $file = get_post_data('file');
        $status = get_post_data('status');

        //名前のチェック
        if (check_empty($name) === true) {
            $error[] = '名前を入力してください';
        }

        //値段のチェック
        if (check_empty($price) === true) {
            $error[] = '値段を入力してください';
        } else if (check_half_number($price) === false) {
            $error[] = '値段は半角数字を入力してください';
        }

        //個数のチェック
        if (check_empty($piece) === true) {
            $error[] = '個数を入力してください';
        } else if (check_half_number($piece) === false) {
            $error[] = '個数は半角数字で入力してください';
        }

        //ファイルのチェック
        if (check_empty($file) === true) {
            $error[] = 'ファイルを選択してください';
        } else if (check_file($file) === false) {
            $error[] = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です';
        }
        //ステータスのチェック
        if (check_status($status) === false) {
            $error[] = '公開ステータスの値が不正です';
        }

        if (empty($error)) {

            $date = date('y:m:d H:i:s');

            mysqli_autocommit($link, false);

            $result = insert_information_table($name, $price, $date, $status, $file, $link);

            // 新しく追加した商品のdrink_idを取得
            $drink_id = mysqli_insert_id($link);

            if ($result === true) {
                $result = insert_stock_table($drink_id, $piece, $date, $link);
            }

            if ($result === true) {
                // 処理確定
                mysqli_commit($link);
                $result = '新規商品追加成功';
            } else {
                // 処理取消
                mysqli_rollback($link);
            }
        }
    }
    //在庫変更
    if (isset($_POST['stock_change'])) {
        $drink_id = get_post_data('drink_id');
        $stock = get_post_data('stock');

        if (is_numeric($drink_id) === false) {
            $error[] = 'idの値が不正です';
        }

        if (check_empty($stock) === true) {
            $error[] = '在庫数を入力してください';
        } else if (check_half_number($stock) === false) {
            $error[] = '在庫数は半角数字で入力してください';
        }
        if (empty($error)) {
            $result = update_stock_table($stock, $drink_id, $link);
        }
    }

    //ステータス変更

    if (isset($_POST['status_change'])) {

        $drink_id = get_post_data('drink_id');
        $status = get_post_data('status');

        if (is_numeric($drink_id) === false) {
            $error[] = 'idの値が不正です';
        }

        if (check_status($status) === false) {
            $error[] = 'ステータスが不正です';
        }
        if (empty($error)) {
            $result  = update_status_table($status, $drink_id, $link);
        }
    }
}


$drink_data =  get_table_list($link);
close_db_connect($link);

// HTMLとして表示する文字をHTMLエンティティに変換する
entity_assoc_array($drink_data);

include_once '../../include/view/tool.php';
