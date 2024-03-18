<?php

require_once '../../include/conf/const.php';

require_once '../../include/model/board.php';

$name = '';
$comment = '';
$board_data = [];
$err_msg = [];


// DB接続
$link = get_db_connect();
$request_method = get_request_method();
if ($request_method === 'POST') {
    $name = get_post_data('name');
    $comment = get_post_data('comment');

    //nameのバリデーション
    if (check_empty($name) === true) {
        $err_msg[] = '名前を入力してください';
    }

    if (check_name($name) !== TRUE) {
        $err_msg[] = '名前は20文字以内で入力してください';
    }

    if (check_empty($comment) === true) {
        $err_msg[] = 'ひとことを入力してください';
    }
    if (check_comment($comment) !== TRUE) {
        $err_msg[] = 'ひとことは100文字以内で入力してください';
    }
    // 正常処理
    if (empty($err_msg)) {

        // テーブルへデータを挿入(INSERT)
        insert_table($name, $comment, $link);
    }
}

// テーブルからデータを取得(SELECT)
$board_data = get_table_list($link);


// DB切断
close_db_connect($link);

// HTMLとして表示する文字をHTMLエンティティに変換する
entity_assoc_array($board_data);

include_once '../../include/view/board.php';
