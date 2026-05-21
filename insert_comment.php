<?php
include "judgelogin.php";
date_default_timezone_set('America/Los_Angeles');

verify_csrf();

$userid = (int) $_SESSION["userid"];
$s_plan_id = input_int($_GET, 'plan', 0);
$p_id = input_int($_POST, 'pid', 0);
$detail = trim($_POST['comment'] ?? '');

if ($s_plan_id <= 0 || $detail === '') {
    redirect_to('plan_page.php');
}

$timestamp = date('Y-m-d H:i:s');
db_execute(
    $connection,
    "INSERT INTO comments (s_plan_id, user_id, p_id, detail, time) VALUES (?, ?, ?, ?, ?)",
    "iiiss",
    $s_plan_id,
    $userid,
    $p_id,
    $detail,
    $timestamp
);

mysqli_close($connection);
redirect_to("comment.php?splanid=" . $s_plan_id);
