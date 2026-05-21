<?php
require('judgelogin.php');
<<<<<<< ours
require('connect.php');
$plan = isset($_GET['plan']) ? (int) $_GET['plan'] : 0;
$user = (int) $_SESSION['userid'];

if ($plan > 0) {
    $checkSql = "SELECT 1 FROM ongoingplan_user WHERE ongoingplan_id = ? AND user_id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($connection, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $plan, $user);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) === 0) {
        $insertSql = "INSERT INTO ongoingplan_user (user_id, ongoingplan_id) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($connection, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "ii", $user, $plan);
        mysqli_stmt_execute($insertStmt);
        mysqli_stmt_close($insertStmt);
    }

    mysqli_stmt_close($checkStmt);
=======

verify_csrf();

$plan = input_int($_POST, 'plan', 0);
$user = (int) $_SESSION['userid'];

if ($plan > 0) {
    $row = db_one($connection, "SELECT 1 FROM ongoingplan_user WHERE ongoingplan_id = ? AND user_id = ? LIMIT 1", "ii", $plan, $user);
    if (!$row) {
        db_execute($connection, "INSERT INTO ongoingplan_user (user_id, ongoingplan_id) VALUES (?, ?)", "ii", $user, $plan);
    }
>>>>>>> theirs
}

mysqli_close($connection);
?>
<html>
<head>
<meta http-equiv="refresh" content="1; url=plan_page.php">
</head>
<body>
<h1>您已成功加入该计划！页面将在2秒后自动跳转</h1>
</body>
</html>
