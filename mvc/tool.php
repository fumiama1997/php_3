<?php

require_once '../../include/conf/drink_const.php';

require_once '../../include/model/drink.php';

$err_msg = [];
$drink_data = [];
$change = '';
// DB接続
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    // リクエストメソッド取得
    $request_method = get_request_method();
    if ($request_method === 'POST') {

        $name = get_post_data('name');
        $price = get_post_data('price');
        $piece = get_post_data('piece');
        $file = get_post_data('file');
        $status = get_post_data('status');

        //名前のチェック
        if (check_empty($name) === true) {
            $err_msg[] = '名前を入力してください';
        }

        //値段のチェック
        if (check_empty($price) === true) {
            $err_msg[] = '値段を入力してください';
        } else if (check_half_number($price) === false) {
            $err_msg[] = '値段は半角数字を入力してください';
        }

        //個数のチェック
        if (check_empty($piece) === true) {
            $err_msg[] = '個数を入力してください';
        } else if (check_half_number($piece) === false) {
            $err_msg[] = '個数は半角数字で入力してください';
        }

        //ファイルのチェック
        if (check_empty($file) === true) {
            $err_msg[] = 'ファイルを選択してください';
        } else if (check_file($file) === false) {
            $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です';
        }
        //ステータスのチェック
        if (check_status($status) === false) {
            $err_msg[] = '公開ステータスの値が不正です';
        }

        if (empty($err_msg)) {

            $date = date('y:m:d H:i:s');

            mysqli_autocommit($link, false);


            insert_information_table($name, $price, $date, $status, $file, $link);

            // 新しく追加した商品のdrink_idを取得
            $drink_id = mysqli_insert_id($link);

            insert_stock_table($drink_id, $piece, $date, $link);

            if (count($err_msg) === 0) {
                // 処理確定
                mysqli_commit($link);
                $change = '新規商品追加成功';
            } else {
                // 処理取消
                mysqli_rollback($link);
            }
        }
    }
}
$drink_data =  get_table_list($link);
close_db_connect($link);

// HTMLとして表示する文字をHTMLエンティティに変換する
entity_assoc_array($drink_data);

include_once '../../include/view/tool.php';
