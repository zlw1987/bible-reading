<?php
require('connect.php');
require_once 'security.php';

$id = 1;
$row = db_one($connection, "SELECT startdate FROM s_plan WHERE id = ? LIMIT 1", "i", $id);
$day = $row ? $row['startdate'] : '';
echo h($day);

echo h((string) ((strtotime(date("j F Y")) - strtotime("24 December 2020")) / (60*60*24))) . "<br>";
mysqli_close($connection);
