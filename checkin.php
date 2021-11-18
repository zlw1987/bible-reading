<?
require('judgelogin.php');
$userid = $_SESSION["userid"];
$planid = $_GET['planid'];
$page = $_GET['page'];
$plan = $_GET['plan'];
echo $plan;
$sql = "SELECT * FROM `s_checkins` WHERE user_id = $userid and s_plan_id = $planid;";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$n = mysqli_affected_rows($connection);
if ($n == 0){
    $sql = "INSERT INTO `s_checkins` (`id`, `user_id`, `s_plan_id`) VALUES (NULL, $userid, $planid);";
    mysqli_query($connection, $sql) or die(mysqli_error());
}
// Close connection
mysqli_close($connection);
$url = "home.php?page=".$page."&plan=".$plan;

?>
<script type="text/javascript">
    var url=<?php echo json_encode($url); ?>;
    window.location= url;
</script>
