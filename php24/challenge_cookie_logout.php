<?php

$now = time();

// Cookieを削除
setcookie('visited', $count, $now - 3600);
setcookie('last_time_access', $date_time, $now - 3600);

//リダイレクト
header('Location: challenge_cookie.php');
exit;
