<?php

// セッション開始
session_start();
// セッション名取得 ※デフォルトはPHPSESSID
$session_name = session_name();
// セッション変数を全て削除
$_SESSION = [];

// Cookieを削除
setcookie('last_time_access', $date_time, $now - 3600);

// ユーザのCookieに保存されているセッションIDを削除
if (isset($_COOKIE[$session_name])) {
    // sessionに関連する設定を取得
    $params = session_get_cookie_params();

    // sessionに利用しているクッキーの有効期限を過去に設定することで無効化
    setcookie(
        $session_name,
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 現在のセッションに 関連づけられたすべてのデータを破棄する
session_destroy();

// ログアウトの処理が完了したら元のページへ戻る
header('Location:challenge_session.php');
exit;
