<?php
include "judgelogin.php";

verify_csrf();

$userid = (int) $_SESSION["userid"];
$s_plan_id = input_int($_POST, 'plan', 0);
$id = input_int($_POST, 'id', 0);

if ($s_plan_id <= 0 || $id <= 0) {
    redirect_to('plan_page_en.php');
}

$row = db_one($connection, "SELECT user_id FROM comments WHERE id = ? LIMIT 1", "i", $id);
if ($row && (int) $row['user_id'] === $userid) {
    db_execute($connection, "DELETE FROM comments WHERE id = ? OR p_id = ?", "ii", $id, $id);
}

mysqli_close($connection);
redirect_to("comment_en.php?splanid=" . $s_plan_id);
