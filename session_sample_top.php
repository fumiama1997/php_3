<?php
/*
*  ログインページ
*
*  セッションの仕組み理解を優先しているため、一部処理はModelへ分離していません
*  また処理はセッション関連の最低限のみ行っており、本来必要な処理も省略しています
*/
require_once '../include/conf/const.php';
require_once '../include/model/function.php';
// セッション開始
session_start();
// セッション変数からログイン済みか確認
if (isset($_SESSION['user_id']) === TRUE) {
    // ログイン済みの場合、ホームページへリダイレクト
    header('Location: session_sample_home.php');
    exit;
}
// セッション変数からログインエラーフラグを確認
if (isset($_SESSION['login_err_flag']) === TRUE) {
    // ログインエラーフラグ取得
    $login_err_flag = $_SESSION['login_err_flag'];
    // エラー表示は1度だけのため、フラグをFALSEへ変更
    $_SESSION['login_err_flag'] = FALSE;
} else {
    // セッション変数が存在しなければエラーフラグはFALSE
    $login_err_flag = FALSE;
}
// Cookie情報からメールアドレスを取得
if (isset($_COOKIE['email']) === TRUE) {
    $email = $_COOKIE['email'];
} else {
    $email = '';
}
// 特殊文字をHTMLエンティティに変換
$email = entity_str($email);
include_once '../include/view/session_sample_top.php';
