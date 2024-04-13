<?php
require_once '../../include/conf/drink_const.php';

require_once '../../include/model/drink.php';

$error = [];
// DB接続
$link = get_db_connect();

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {

    if (isset($_POST['information'])) {

        $money = get_post_data('money');

        //moneyのバリデーション
        if (check_empty($money) === true) {
            $error[] = 'お金を投入してください';
        } else if ((is_numeric($money)) === false) {
            $error[] = 'お金は半角数字で入力してください';
        }

        if (isset($_POST['drink_id']) === false) {
            $error[] = '商品を選択してください';
        } else {

            $drink_id = get_post_data('drink_id');

            if ((check_empty($drink_id)) === true) {
                $error[] = 'drink_idが空です';
            } else if ((is_numeric($drink_id)) === false) {
                $error[] = 'drink_idが不正です';
            } else {
                //drink_idに紐付いたレコードを取得
                $drink_data = get_table_individual_list($link, $drink_id);

                // 商品が見つからない場合
                if (empty($drink_data)) {
                    $error[] = '商品が見つかりません';
                }

                //在庫数のバリデーション
                if ((intval($drink_data[0]['stock']) <= 0)) {
                    $error[] = '在庫がありません';
                }

                //公開ステ―タスのバリデーション
                if ($drink_data[0]['status'] === '0') {
                    $error[] = 'ステータスが非公開の為購入できません';
                }

                //投入額よりも商品の価格が高くないか
                if (empty($error)) {
                    if ((check_not_enough($money, $drink_data[0]['price'])) === true) {
                        $error[] = 'お金が足りません！';
                    }
                }

                //if(empty($error)) update 在庫数が一個減るように
                if (empty($error)) {
                    if (update_stock_quantity($link, $drink_id)) {
                        // おつりの計算
                        $change = $money - $drink_data[0]['price'];
                    } else {
                        $error[] = '在庫数の更新に失敗しました';
                    }
                }
            }
        }
    }
}
close_db_connect($link);


include_once '../../include/view/result.php';
