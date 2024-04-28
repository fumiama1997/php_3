<?php

date_default_timezone_set('Asia/Tokyo');

if (isset($_COOKIE['visited']) === TRUE){
    $count = $_COOKIE['visited'] + 1;
} else {
    $count= 1;
}

$date = new DateTime('now');
$date_time = $date->format('Y年m月d日 H時i分s秒');


setcookie('visited',$count,time() + 60 * 60 * 24 * 365);
setcookie('last_time_access',$date_time,time() + 60 * 60 * 24 * 365);

if($count === 1){
    $count_comment = '初めてのアクセスです';
    $last_date_time = '';
} else{
    $count_comment = '合計'. $count . '回目のアクセスです';
    $last_date_time = $_COOKIE['last_time_access'];
}


$count_comment = htmlspecialchars($count_comment,ENT_QUOTES, 'UTF-8');
$date_time = htmlspecialchars($date_time,ENT_QUOTES, 'UTF-8');
$last_date_time = htmlspecialchars($last_date_time,ENT_QUOTES, 'UTF-8');


include_once '../../include/view/challenge_cookie.php';