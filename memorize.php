<?php
session_start();
require_once 'security.php';
require('translation_map.php');

$memorize = $_GET['mem'] ?? '';
$sp1 = explode(' ', $memorize);
$book_name = $sp1[0] ?? '';
$book = $book_map[$book_name] ?? '';
$sp2 = explode(':', $sp1[1] ?? '0:0');
$chapter = max(1, (int) ($sp2[0] ?? 1));
$verse = max(1, (int) ($sp2[1] ?? 1));
$s_plan_id = input_int($_GET, 'splanid', 0);

if ($book === '' || $s_plan_id <= 0) {
    redirect_to('plan_page.php');
}

$book_url = 'https://springbible.fhl.net/Bible2/cgic201/read001.cgi?len=1&s=1&ft=15&na=' . rawurlencode($book) . '&ch=' . $chapter . '&v=' . $verse;
$plan = (int) ($_SESSION['plan'] ?? 0);
$page = (int) ($_SESSION['page'] ?? 0);
$back_url = "home.php?plan=" . $plan . "&page=" . $page;
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
</head>
<body>
    <div class = "container">
    <iframe id="preview-frame" src="<?php echo h($book_url); ?>" name="preview-frame" frameborder="0" style="height:6700px;width:100%;"></iframe>
    </div>
    <br>
    <div class = "w3-cell-row w3-top w3-light-grey" style="padding: 5px 5px 10px 5px">
        <div class = "w3-cell w3-center">
            <h3>背诵经文</h3>
            <a class="w3-button w3-round w3-orange" href="<?php echo h($back_url); ?>">返回</a>
        </div>
    </div>
</body>
</html>
