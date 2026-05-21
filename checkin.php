<?php
require('judgelogin.php');

verify_csrf();

$userid = (int) $_SESSION["userid"];
$planid = input_int($_POST, 'planid', 0);
$page = input_int($_POST, 'page', 0);
$plan = input_int($_POST, 'plan', 0);

if ($planid <= 0 || $plan <= 0) {
    redirect_to('plan_page.php');
}

$row = db_one($connection, "SELECT id FROM s_checkins WHERE user_id = ? AND s_plan_id = ? LIMIT 1", "ii", $userid, $planid);
if (!$row) {
    db_execute($connection, "INSERT INTO s_checkins (user_id, s_plan_id) VALUES (?, ?)", "ii", $userid, $planid);
}

mysqli_close($connection);
redirect_to("home.php?page=" . $page . "&plan=" . $plan);
