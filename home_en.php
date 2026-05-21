<?php
require('judgelogin.php');
$username = $_SESSION["username"];
$userid = (int) $_SESSION["userid"];
$fname = $_SESSION["fname"];
if (input_int($_GET, 'plan', 0) > 0){
    $ongoingplan_id = input_int($_GET, 'plan', 0);
    $_SESSION['plan'] =  $ongoingplan_id;
}elseif (!empty($_SESSION['plan'])){
    $ongoingplan_id = (int) $_SESSION['plan'];
}else{
    header('Location: plan_page.php');
    exit;
}

//require_once 'vendor/autoload.php';
//set time
date_default_timezone_set("America/Los_Angeles");
$mydate=getdate(date("U"));
<<<<<<< ours
$month = date('m');

//$ongoingplan_id = $_GET['plan'];
=======
$month = date("m");

>>>>>>> theirs

//set dictionary for bible url
require('translation_map.php');

//get user small group
<<<<<<< ours
$sql = "SELECT smallgroup FROM users WHERE id = " . $userid;
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
while ($r = mysqli_fetch_assoc($resultset)){
    $smallgroup = $r['smallgroup'];
}


//check which day should be showing
if (isset($_GET['page'])){
    $dif_date = $_GET['page'];
=======
$userRow = db_one($connection, "SELECT smallgroup FROM users WHERE id = ? LIMIT 1", "i", $userid);
$smallgroup = $userRow ? (int) $userRow['smallgroup'] : 0;

//check which day should be showing
if (isset($_GET['page'])){
    $dif_date = input_int($_GET, 'page', 0);
>>>>>>> theirs
}else{
    $dif_date = 0;
}
$_SESSION['page'] = $dif_date;

$pre = $dif_date + 1;
$next = $dif_date - 1;

$cur_month = date("m",strtotime("-$dif_date day"));
$cur_day = date("d",strtotime("-$dif_date day"));
if ($dif_date == 0){
    $planday = "Today's Devotion";
}else{
    $planday = $cur_month."/". $cur_day."'s Devotion";
}

//get the day of the plan
<<<<<<< ours
$sql = "SELECT startdate,plan_id FROM ongoingplan WHERE id = " . $ongoingplan_id;
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$results = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $results[] = $r;
}
$startdate = $results[0]['startdate'];
$plan_id = $results[0]['plan_id'];
=======
$planRow = db_one($connection, "SELECT startdate, plan_id FROM ongoingplan WHERE id = ? LIMIT 1", "i", $ongoingplan_id);
if (!$planRow) {
    redirect_to('plan_page.php');
}
$startdate = $planRow['startdate'];
$plan_id = (int) $planRow['plan_id'];
>>>>>>> theirs
$today = date("Y-m-j",strtotime("-$dif_date day"));
$day = ceil((strtotime($today) - strtotime($startdate)) / (60*60*24)) + 1;

//get the daily plan detail
<<<<<<< ours
$sql = "SELECT * FROM `s_plan` WHERE day = $day AND plan_id = " . $plan_id;
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$result = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $result[] = $r;
}
=======
$result = db_all($connection, "SELECT * FROM s_plan WHERE day = ? AND plan_id = ?", "ii", $day, $plan_id);
>>>>>>> theirs
$n = count($result);
if ($n > 0){
    $detail = $result[0]['detail'];
    $plandetail = explode("，", $detail);
    $memorize = $result[0]['memorize'];
    $memorize_detail = explode(" ", $memorize);

}else{
    $plandetail = array("Remember God is All and in All!");
    $memorize = '';
}
$_SESSION['plandetail'] = $plandetail;

//get all checked in users
if ($result){
<<<<<<< ours

    $s_plan_id = $result[0]['id'];
    $sql = "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r, users AS z WHERE r.s_plan_id = " . $s_plan_id . " AND r.user_id = z.id";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
    $checked = array();
    //check if user has checked in
    $bl = 0;
    while ($r = mysqli_fetch_assoc($resultset)){
        $checked[] = $r;
        if ($r['id'] == $userid){
=======
    
    $s_plan_id = (int) $result[0]['id'];
    $checked = db_all($connection, "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r INNER JOIN users AS z ON r.user_id = z.id WHERE r.s_plan_id = ?", "i", $s_plan_id);
    //check if user has checked in
    $bl = 0;
    foreach ($checked as $r) {
        if ((int) $r['id'] === $userid) {
>>>>>>> theirs
            $bl = 1;
        }
    }
    $a = count($checked);
}

//get checked in users in the same small group as user
$checked_group = array();
$a_group = 0;
if ($result){
<<<<<<< ours
    $s_plan_id = $result[0]['id'];
    $sql = "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r, users AS z WHERE r.s_plan_id = " . $s_plan_id . " AND r.user_id = z.id AND z.smallgroup = " . $smallgroup;
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());

    //check if user has checked in
    while ($r = mysqli_fetch_assoc($resultset)){
        $checked_group[] = $r;
    }
=======
    $s_plan_id = (int) $result[0]['id'];
    $checked_group = db_all($connection, "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r INNER JOIN users AS z ON r.user_id = z.id WHERE r.s_plan_id = ? AND z.smallgroup = ?", "ii", $s_plan_id, $smallgroup);
>>>>>>> theirs
    $a_group = count($checked_group);
}

// Close connection
mysqli_close($connection);
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
</head>

<body>
    <div>
        <p class = "w3-display-topright	w3-padding-large"><a href = "plan_page_en.php">Back to Plans</a></p>
    </div>
    <br>
    <div class="w3-container">
        <table class="w3-table w3-border w3-striped">
            <tr>
<<<<<<< ours
                <th><p>Welcome <?php echo $fname;?>,<br>
                    Today is <?php echo  $mydate['month']." ". $mydate['mday'].", ". $mydate['year'].", ". $mydate['weekday'];?></p>
=======
                <th><p>Welcome <?php echo h($fname);?>,<br>
                    Today is <?php echo h($mydate['month']." ". $mydate['mday'].", ". $mydate['year'].", ". $mydate['weekday']);?></p>
>>>>>>> theirs
                    <div class="w3-cell-row">
                        <div class = "w3-left-align w3-cell">
                            <a href="<?php echo "home_en.php?page=".$pre."&plan=".$ongoingplan_id ?>">Prev</a>&nbsp;
                            <a href="<?php echo "home_en.php?plan=".$ongoingplan_id ?>">Today</a>&nbsp;
                            <a href="<?php echo "home_en.php?page=".$next."&plan=".$ongoingplan_id ?>"> Next</a>
                        </div>
                        <div class = "w3-right-align w3-cell">
                            <a class="w3-btn w3-black w3-round w3-padding-small" href="calendar_en.php?plan=<?php echo $ongoingplan_id ?>">Check Calendar</a>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
<<<<<<< ours
                <th><p><?php echo $planday?></p></th>
=======
                <th><p><?php echo h($planday)?></p></th>
>>>>>>> theirs
            </tr>
            <tr>
                <th>
                <ul class="w3-ul w3-card-4 w3-hoverable" style="width:50%" >
                <?php
                    if ($n > 0){
                        foreach($plandetail as $value){
                            $url = explode(" ", $value);
                            $chapter = $url[1];
                            $book_name = substr($url[0],0,strlen($url[0])-3);
                            if (array_key_exists($book_name, $book_en)){
                                $book_name_en =  $book_en[$book_name];
                            }else{
                                $book_name_en = $book_name;
                            }
                            if (array_key_exists($book_name, $book_map)){
                                $book = $book_map[$book_name]; 
                                $book_url = 'https://www.o-bible.com/cgibin/ob.cgi?version=kjv&&book='.$book.'&chapter='.$chapter.'&p';
                                $version = "kjv";
                                echo "<li>".'<a href = "chapter_detail_en.php?splanid='.$s_plan_id.'&book='.$book.'&chapter='.$chapter.'&version='.$version.'">'.$book_name_en." ".$chapter.'</a></li>';
                            }else{
                                echo "<li>".$book_name_en." ".$chapter."</li>";
                            }
                        }
                    echo '<li class = "w3-light-grey"><a href = "comment_en.php?splanid='.$s_plan_id.'">Share today'."'s thoughts</a></li>";
                    }else{
                        echo "<li>".$plandetail[0]."</li>";
                    }
                ?>
                
                </ul>
                </th>
            </tr>
            <?php
                if ($memorize){
                    echo '<tr><th>Memorize Verse</th></tr><tr><th><ul class="w3-ul w3-card-4 w3-hoverable" style="width:50%"><li>'.$book_en[$memorize_detail[0]]." ".$memorize_detail[1].'</li></ul></th></tr>';
                }
            ?>
            <tr>
                <th>
                    <p>Completed</p>
                </th>
            </tr>
            <tr>
                <td>
                    <div class="w3-bar-block w3-light-grey w3-cell" style="width:80px">
                        <button class="w3-bar-item w3-button tablink w3-grey" onclick="openChecked(event, 'smallgroup')">My Group</button>
                        <button class="w3-bar-item w3-button tablink" onclick="openChecked(event, 'all')">All people</button>
                    </div>
                    <div class = "w3-cell" style="margin-left:130px">
                        <div id="smallgroup" class="w3-container checked" style="display:block">
                            <?php
                                foreach($checked_group as $b_group){
<<<<<<< ours
                                    $printname_group = $b_group['fname']." ". $b_group['lname']."; ";
                                        echo $printname_group;
=======
                                        $printname_group = $b_group['fname']." ". $b_group['lname']."; ";
                                        echo h($printname_group);
>>>>>>> theirs
                                        }
                                if ($a_group == 0 && $n > 0){
                                    echo "Open your bible and become the first!";
                                }elseif ($n == 0){
                                    echo "Only God today!";
                                }
                            ?>                            
                        </div>
                        <div id="all" class="w3-container checked" style="display:none">
                            <?php
                                foreach($checked as $b){
                                        $printname = $b['fname']." ". $b['lname']."; ";
                                        echo h($printname);
                                        }
                                if ($a == 0 && $n > 0){
                                    echo "Open your bible and become the first!";
                                }elseif ($n == 0){
                                    echo "God only today!";
                                }
                            ?>      
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <?php
                        if ($result){
                            if ($bl == 1){
                                echo "You have completed today's devotion!";
                            }else{
<<<<<<< ours
                    ?>
                    <form method="post" action="<?php echo "checkin.php?page=".$dif_date."&planid=".$s_plan_id."&plan=".$ongoingplan_id ?>">
                        <input type="submit" name="submit" value="Check" />
                    </form>
                    <?php }}

=======
                            ?>
                                <form method="post" action="checkin.php"> 
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="page" value="<?php echo (int) $dif_date; ?>">
                                <input type="hidden" name="planid" value="<?php echo (int) $s_plan_id; ?>">
                                <input type="hidden" name="plan" value="<?php echo (int) $ongoingplan_id; ?>">
                                <input type="submit" name="submit" value="Check"> 
                                </form>
                        <?php }}
                        
>>>>>>> theirs
                    ?>
                </th>
            </tr>
        </table>
<<<<<<< ours
        <p class = "w3-left w3-button w3-black w3-round w3-circle"><a href="home.php?plan=<?php echo $ongoingplan_id; ?>">中文</a></p>
=======
        <p class = "w3-left w3-button w3-black w3-round w3-circle""><a href = "home.php?plan=<?php echo $ongoingplan_id; ?>">中文</a></p>
>>>>>>> theirs
        <div class = "w3-right w3-padding-16">
        <form method='post' action="" style="padding-top:6px">
            <?php echo csrf_field(); ?>
                <input type="submit" value="Logout" name="but_logout">
        </form>
    </div>
    </div>
<script>
    function openChecked(evt, Name) {
        var i, x, tablinks;
        x = document.getElementsByClassName("checked");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-grey", ""); 
        }
        document.getElementById(Name).style.display = "block";
        evt.currentTarget.className += " w3-grey";
    }
</script>
</body>
</html>