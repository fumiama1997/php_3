<?php

date_default_timezone_set('Asia/Tokyo');

session_start();
if (isset($_SESSION['count']) === TRUE) {
    $_SESSION['count']++;
} else {
    $_SESSION['count'] = 1;
}

//日付けの取得
$date = new DateTime('now');
$date_time = $date->format('Y年m月d日 H時i分s秒');

setcookie('last_time_access', $date_time, time() + 60 * 60 * 24 * 365);


if ($_SESSION['count'] === 1) {
    $count_comment = '初めてのアクセスです';
    $last_date_time = '';
} else {
    $count_comment = '合計' . $_SESSION['count'] . '回目のアクセスです';
    $last_date_time = $_COOKIE['last_time_access'];
}

// HTMLタグや特殊文字をエンティティに変換する
$count_comment = htmlspecialchars($count_comment, ENT_QUOTES, 'UTF-8');
$_SESSION['count'] = htmlspecialchars($_SESSION['count'], ENT_QUOTES, 'UTF-8');
$date_time = htmlspecialchars($date_time, ENT_QUOTES, 'UTF-8');
$last_date_time = htmlspecialchars($last_date_time, ENT_QUOTES, 'UTF-8');

include_once '../../include/view/challenge_session.php';