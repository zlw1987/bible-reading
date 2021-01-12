<?
require('connect.php');
$mydate=getdate(date("U"));
$today = date_create(date("Y-m-j"));

$sql = "SELECT `startdate` FROM `s_plan` WHERE `id` = 1";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$results = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $results[] = $r;
}
foreach ($results as $o){
    $day = $o[startdate];
}
echo $day;

echo((strtotime(date("j F Y")) - strtotime("24 December 2020"))/ (60*60*24) ."<br>");
mysqli_close($connection);
?>