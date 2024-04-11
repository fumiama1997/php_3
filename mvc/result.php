<?php
require_once '../../include/conf/drink_const.php';

require_once '../../include/model/drink.php';

$error = [];
// DB接続
$link = get_db_connect();
$money_result = true;

// リクエストメソッド取得
$request_method = get_request_method();
if ($request_method === 'POST') {
    if (isset($_POST['information'])) {

        //moneyのバリデーション
        if (check_empty($_POST['money']) === true) {
            $error[] = 'お金を投入してください';
            $money_result = false;
        } else if ((is_numeric($_POST['money'])) === false) {
            $error[] = 'お金は半角数字で入力してください';
            $money_result = false;
        } 


        //drink_idのバリデーション
        //isset empty is_numeric 
        if (isset($_POST['drink_id']) === false) {
            $error[] = '商品を選択してください';
        } else {
            $drink_id = get_post_data('drink_id');

            if ((check_empty($drink_id)) === true) {
                $error[] = 'drink_idが空です';
            } else if ((is_numeric($drink_id)) === false) {
                $error[] = 'drink_idが不正です';
            } else {
                $drink_data = get_table_individual_list($link, $drink_id);
                foreach ($drink_data as $key => $value) {
                    $picture = $value["picture"];
                    $name =  $value['name'];
                    $price =  $value['price'];
                    $stock =  $value['stock'];
                    $status = $value['status'];
                }
                $money = get_post_data('money');

                //在庫数のバリデーション
                if ((intval($stock) === 0)) {
                    $error[] = '在庫がありません';
                }

                //公開ステ―タスのバリデーション
                if ($status === '0') {
                    $error[] = 'ステータスが非公開の為購入できません';
                }
                //上記のmoneyのバリデーションを通過しており
                //投入額よりも商品の価格が高くないか
                if ($money_result === true) {
                    if ((check_not_enough($money, $price)) === true) {
                        $error[] = 'お金が足りません！';
                    } else {
                        $change = $money - $price;
                    }
                }
                //if(empty($error)) update 在庫数が一個減るようにする(関数を作る。)
                if (empty($error)) {
                    $result = update_stock_quantity($link, $drink_id);
                    close_db_connect($link);
                }
            }
        }
    }
}

include_once '../../include/view/result.php';
