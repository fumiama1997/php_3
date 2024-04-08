<pre>
    <?php
    session_start();
    print 'セッション名:' . session_name() . "\n";
    print 'セッションID' . session_id() . "\n";
    if (isset($_SESSION['count']) === TRUE) {
        $_SESSION['count']++;
    } else {
        $_SESSION['count'] = 1;
    }
    print $_SESSION['count'] . '回目の訪問です' . "\n";
    ?>
</pre>