<?
require('judgelogin.php');
require('connect.php');
$plan = $_GET['plan'];
$user = $_GET['user'];

$sql = "SELECT * FROM ongoingplan_user AS ou WHERE ou.ongoingplan_id = $plan AND ou.user_id = $user";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$n = mysqli_affected_rows($connection);
if ($n == 0){
   $sql = "INSERT INTO `ongoingplan_user`(`user_id`, `ongoingplan_id`) VALUES ($user,$plan)"; 
   $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
}
// Close connection
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