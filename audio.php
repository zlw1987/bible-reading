<?php
session_start();
require_once 'security.php';
require('translation_map.php');

$chapter = input_int($_GET, 'chapter', 1);
$book = preg_replace('/[^A-Za-z0-9]/', '', $_GET['book'] ?? '');
$s_plan_id = input_int($_GET, 'splanid', 0);
if ($book === '' || $s_plan_id <= 0) {
    redirect_to('plan_page.php');
}

$book_url = 'https://www.biblegateway.com/audio/biblica/ccb/' . rawurlencode($book . '.' . $chapter) . '?interface=amp';
$plan = (int) ($_SESSION['plan'] ?? 0);
$page = (int) ($_SESSION['page'] ?? 0);
$back_url = "home.php?plan=" . $plan . "&page=" . $page;

$detail = $_SESSION['plandetail'] ?? array();
$n = count($detail);
$book_arr = array();
$chapter_arr = array();
$cur = 0;
for ($i = 0; $i < $n; $i++) {
    $temp = explode(' ', $detail[$i]);
    if (count($temp) < 2) {
        continue;
    }
    $chapter_arr[$i] = (int) $temp[1];
    $book_name = substr($temp[0], 0, strlen($temp[0]) - 3);
    if (!isset($audio_map[$book_name])) {
        continue;
    }
    $book_arr[$i] = $audio_map[$book_name];
    if ($book_arr[$i] === $book && $chapter_arr[$i] === $chapter) {
        $cur = $i;
    }
}

$prev_url = $cur > 0 && isset($book_arr[$cur - 1], $chapter_arr[$cur - 1])
    ? '<a class="w3-button w3-round w3-orange" href="audio.php?book=' . h($book_arr[$cur - 1]) . '&chapter=' . (int) $chapter_arr[$cur - 1] . '&splanid=' . $s_plan_id . '">前一章</a>'
    : '<div class="w3-button w3-round">从这里开始</div>';
$next_url = $cur < $n - 1 && isset($book_arr[$cur + 1], $chapter_arr[$cur + 1])
    ? '<a class="w3-button w3-round w3-orange" href="audio.php?book=' . h($book_arr[$cur + 1]) . '&chapter=' . (int) $chapter_arr[$cur + 1] . '&splanid=' . $s_plan_id . '">后一章</a>'
    : '<a class="w3-button w3-round w3-orange" href="comment.php?splanid=' . $s_plan_id . '">每日灵修分享</a>';
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
</head>
<body style="background: #bf300d;">
    <br><br>
    <div class="container">
        <iframe id="preview-frame" src="<?php echo h($book_url); ?>" name="preview-frame" frameborder="0" style="height:50%;width:100%;"></iframe>
    </div>
    <br>
    <div class="w3-cell-row w3-top w3-light-grey" style="padding: 2% 5px 2% 5px">
        <div class="w3-cell w3-left-align" style="width:33%"><?php echo $prev_url; ?></div>
        <div class="w3-cell w3-center" style="width:33%"><a class="w3-button w3-round w3-orange" href="<?php echo h($back_url); ?>">返回</a></div>
        <div class="w3-cell w3-right-align" style="width:33%"><?php echo $next_url; ?></div>
    </div>
</body>
</html>
