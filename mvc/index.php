<?php
require_once '../../include/conf/drink_const.php';

require_once '../../include/model/drink.php';


$error = [] ;
$goods_data = [];

// DB接続
$link = get_db_connect();

//クエリ記載　クエリの実行　データを配列に入れる　
$goods_data =  get_table_list($link);

close_db_connect($link);


// HTMLとして表示する文字をHTMLエンティティに変換する
entity_assoc_array($goods_data);


include_once '../../include/view/index.php';