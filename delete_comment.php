<?
include "judgelogin.php";
$userid = $_SESSION["userid"];

if ($_GET['plan']){
    $s_plan_id = $_GET['plan'];
}else{
    header("Location: plan_page.php"); 
    exit;
}

if ($_GET['id']){
    $id = $_GET['id'];

    //check if the comment belongs to the user
    $sql = "SELECT user_id FROM comments WHERE id = $id";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
    while ($r = mysqli_fetch_assoc($resultset)){
        $u_id = $r[user_id];
    } 
    
    //delete the comments and all its replies
    if ($u_id == $userid){
        $sql = "DELETE FROM comments WHERE id = $id";
        mysqli_query($connection, $sql) or die(mysqli_error());
        $sql = "DELETE FROM comments WHERE p_id = $id";
        mysqli_query($connection, $sql) or die(mysqli_error());
    }
}
// Close connection
mysqli_close($connection);

header("Location: comment.php?splanid=$s_plan_id"); 
exit;
?>