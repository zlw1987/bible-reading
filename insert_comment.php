<?
include "judgelogin.php";
date_default_timezone_set('America/Los_Angeles');
$userid = $_SESSION["userid"];
$s_plan_id = $_GET['plan'];
if (isset($_POST['pid'])){
    $p_id = $_POST['pid'];
}else{
    $p_id = 0;
}
$detail = $_POST['comment'];

$timestamp = date('Y-m-d H:i:s');

$sql = "INSERT INTO `comments` (`s_plan_id`, `user_id`, `p_id`, `detail`, `time`) VALUES ($s_plan_id, $userid, $p_id, '$detail', '$timestamp')";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());

// Close connection
mysqli_close($connection);

header("Location: comment.php?splanid=$s_plan_id"); 
exit;

?>