<?
require('judgelogin.php');
require('connect.php');
$userid = $_SESSION["userid"];
$page = $_SESSION["page"];
$plan = $_SESSION['plan'];
$s_plan_id = $_GET['splanid'];
$back_url = "home_en.php?plan=".$plan."&page=".$page;
date_default_timezone_set("America/Los_Angeles");

$sql = "SELECT smallgroup FROM users WHERE id = $userid";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
while ($r = mysqli_fetch_assoc($resultset)){
    $smallgroup = $r[smallgroup];
} 

//get comments
$sql = "SELECT comments.*, users.fname, users.lname, users.smallgroup, users.avatar FROM comments INNER JOIN users ON comments.user_id = users.id AND comments.s_plan_id = $s_plan_id";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$all_comments = array();
$group_comments = array();
$group_reply = array();
$all_reply = array();
while ($r = mysqli_fetch_assoc($resultset)){
    //determine if this is a original comment or relple
    if ($r[p_id] == 0){
        if ($r[smallgroup] == $smallgroup){
            $group_comments[] = $r;
        }
        $all_comments = $r;
    }else{
        if ($r[smallgroup] == $smallgroup){
            $group_reply[$r[p_id]][] = $r;
        }
        $all_reply[$r[p_id]][] = $r;
    }
} 


?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
</head>

<body class = "w3-light-grey">
    <div class="w3-padding" style = "height:79%;overflow: scroll;">
            <ul class="w3-ul w3-hoverable" style="width:100%" >
                <?
                    foreach($group_comments as $gc){
                        echo '<li class = "w3-card w3-round-xlarge w3-white" style = "margin-bottom:5px"><div class = "w3-cell-row w3-text-grey">';
                        echo '<div class = "w3-cell w3-left">'.$gc[fname]."&nbsp;".$gc[lname].'</div>';
                        echo '<div class = "w3-cell w3-right">@'.$gc[time].'</div>';
                        echo '</div><div class = "w3-cell-row">'.$gc[detail];
                        if ($gc[user_id] == $userid){
                            echo '<a class = "w3-right" href = "delete_comment_en.php?plan='.$s_plan_id.'&id='.$gc[id].'">Delete</a>';
                        }else{
                        ?>
                            <a href = "#" onclick="myReply('<?php echo $gc[fname]?>','<?php echo $gc[lname]?>','<?php echo $gc[id]?>')" class = "w3-right">Reply</a></div>
                        <?}
                        echo '<div class = "w3-padding">';
                        foreach($group_reply[$gc[id]] as $gr){
                            echo '<div class = "w3-cell-row  w3-text-grey">';
                            echo '<div class = "w3-cell w3-left">'.$gr[fname]."&nbsp;".$gr[lname].'</div>';
                            echo '<div class = "w3-cell w3-right">@'.$gr[time].'</div>';
                            echo '</div><div class = "w3-cell-row">'.$gr[detail];
                            if ($gr[user_id] == $userid){
                                echo '<a class = "w3-right" href = "delete_comment_en.php?plan='.$s_plan_id.'&id='.$gr[id].'">Delete</a>';
                            }else{
                            ?>
                                <a href = "#" onclick="myReply('<?php echo $gr[fname]?>','<?php echo $gr[lname]?>','<?php echo $gc[id]?>')" class = "w3-right">Reply</a></div>
                            <?}
                        }
                        echo '</div></li>';

                    }
                ?>
                <script>
                    function myReply(a,b,c) {
                        document.getElementById("myTextarea").focus();
                        document.getElementById('myTextarea').value = "Reply to "+a+" "+b+": ";
                        document.getElementById('note').style.display='none';
                        document.getElementById('pid').value = c;
                    }
                </script>
            </ul>
    </div>
    <div class = "w3-white w3-card-4 w3-bottom">
        <div class = "w3-padding-large">
        <form action="insert_comment_en.php?plan=<?echo $s_plan_id?>" id="comment"  method="post">
            <textarea id="myTextarea" style = "width:100%;" name="comment" form="comment" onfocus="document.getElementById('note').style.display='none'" onblur="if(value=='')document.getElementById('note').style.display='block'" required></textarea>
            <input type="hidden" id="pid" name="pid" value=0>
            <div id="note" class="note w3-display-topleft w3-padding-large" onclick = "myComment()">
                <font color="#777">&nbsp;Share your thoughts...</font>
            </div>
            <script>
                    function myComment() {
                        document.getElementById('note').style.display='none';
                        document.getElementById("myTextarea").focus();
                    }
                </script>
            <a class="w3-button w3-small w3-round w3-black" style="margin: 10px 0px 0px 0px" href="<? echo $back_url ?>" />Back</a>
            <input class = "w3-right" style="margin: 14px 0px 0px 0px" type="submit" value = "Submit">
        </form>
        </div>
    </div>
</body>
</html>