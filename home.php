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
=======
$month = date("m");
>>>>>>> theirs



//set dictionary for bible url
require('translation_map.php');

//get user small group
<<<<<<< ours
$sql = "SELECT smallgroup FROM users WHERE id = $userid";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$results = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $results[] = $r;
}
$smallgroup = $results[0]['smallgroup'];

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
    $planday = "今日读经内容";
}else{
    $planday = $cur_month."月". $cur_day."日读经内容";
}

//get the day of the plan
$planRow = db_one($connection, "SELECT startdate, plan_id FROM ongoingplan WHERE id = ? LIMIT 1", "i", $ongoingplan_id);
if (!$planRow) {
    redirect_to('plan_page.php');
}
<<<<<<< ours
$startdate = $results[0]['startdate'];
$plan_id = $results[0]['plan_id'];
=======
$startdate = $planRow['startdate'];
$plan_id = (int) $planRow['plan_id'];
>>>>>>> theirs
$today = date("Y-m-j",strtotime("-$dif_date day"));
$day = ceil((strtotime($today) - strtotime($startdate)) / (60*60*24)) + 1;

//get the daily plan detail
$result = db_all($connection, "SELECT * FROM s_plan WHERE day = ? AND plan_id = ?", "ii", $day, $plan_id);
$n = count($result);
if ($n > 0){
    $detail = $result[0]['detail'];
    $plandetail = explode("，", $detail);
    $memorize = $result[0]['memorize'];
}else{
    $plandetail = array("今天没有读经计划 自己好好安排时间吧");
    $memorize = '';
}
$_SESSION['plandetail'] = $plandetail;

//get all checked in users
$a = 0;
$checked = array();
if ($result){
<<<<<<< ours

    $s_plan_id = $result[0]['id'];
    $sql = "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r, users AS z WHERE r.s_plan_id = $s_plan_id AND r.user_id = z.id";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());

=======
    
    $s_plan_id = (int) $result[0]['id'];
    $checked = db_all($connection, "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r INNER JOIN users AS z ON r.user_id = z.id WHERE r.s_plan_id = ?", "i", $s_plan_id);
>>>>>>> theirs
    //check if user has checked in
    $bl = 0;
    foreach ($checked as $r) {
        if ((int) $r['id'] === $userid) {
            $bl = 1;
        }
    }
    $a = count($checked);
}
$checked_group = array();
//get checked in users in the same small group as user
if ($result){
<<<<<<< ours
    $s_plan_id = $result[0]['id'];
    $sql = "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r, users AS z WHERE r.s_plan_id = $s_plan_id AND r.user_id = z.id AND z.smallgroup = $smallgroup";
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
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
</head>

<body>
    <div>
        <p class = "w3-display-topright	w3-padding-large"><a href = "plan_page.php">返回计划列表</a></p>
    </div>
    <br>
    <div class="w3-container">
        <table class="w3-table w3-border w3-striped">
            <tr>
<<<<<<< ours
                <th><p><?php echo $fname;?> 您好,<br>
                    今天是 <?php echo  $mydate['month']." ". $mydate['mday'].", ". $mydate['year'].", ". $mydate['weekday'];?></p>
=======
                <th><p><?php echo h($fname);?> 您好,<br>
                    今天是 <?php echo h($mydate['month']." ". $mydate['mday'].", ". $mydate['year'].", ". $mydate['weekday']);?></p>
>>>>>>> theirs
                    <div class="w3-cell-row">
                        <div class = "w3-left-align w3-cell">
                            <a href="<?php echo "home.php?page=".$pre."&plan=".$ongoingplan_id ?>">前一天</a>&nbsp;
                            <a href="<?php echo "home.php?plan=" . $ongoingplan_id ?>">回到今天</a>&nbsp;
                            <a href="<?php echo "home.php?page=".$next."&plan=".$ongoingplan_id ?>">下一天</a>
                        </div>
                        <div class = "w3-right-align w3-cell">
                            <a class="w3-btn w3-black w3-round w3-padding-small" href="calendar.php?plan=<?php echo $ongoingplan_id ?>">查看读经日历</a>
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
                <ul class="w3-ul w3-card-4 w3-hoverable" style="width:max-content" >
<<<<<<< ours
                <?php 
=======
                <?php
>>>>>>> theirs
                    foreach($plandetail as $value){
                        $url = explode(" ", $value);
                        $chapter = $url[1];
                        $book_name = substr($url[0],0,strlen($url[0])-3);
                        if (array_key_exists($book_name, $book_map)){
                            $book = $book_map[$book_name]; 
                            $audio = $audio_map[$book_name];
                            $book_url = 'https://www.o-bible.com/cgibin/ob.cgi?version=hgb&&book='.$book.'&chapter='.$chapter.'&p';
                            $version = "hgb";
                            echo "<li>".'<div class = "w3-cell-row"><div class = "w3-cell w3-left-align"><a href = "chapter_detail.php?splanid='.$s_plan_id.'&book='.$book.'&chapter='.$chapter.'&version='.$version.'">'.$value.'</a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class = "w3-cell w3-right-align"><a class = "w3-right-align w3-button w3-small w3-circle w3-ripple w3-black" href = "audio.php?splanid='.$s_plan_id.'&book='.$audio.'&chapter='.$chapter.'"><i class="fas fa-volume-up" style="font-size:12px"></i></a></div></div></li>';
                            
                        }else{
                            echo "<li>".$value."</li>";
                        }
                    }
                    if ($n > 0){
                        echo '<li class = "w3-light-grey"><a href = "comment.php?splanid='.$s_plan_id.'">每日灵修分享</a></li>';
                    }
                ?>
                
                </ul>
                </th>
            </tr>
<<<<<<< ours
            <?php 
=======
            <?php
>>>>>>> theirs
                if (($memorize) and ($memorize !="无")){
                    echo '<tr><th>背诵经节</th></tr><tr><th><ul class="w3-ul w3-card-4 w3-hoverable" style="width:50%"><li><a href = "memorize.php?splanid='.$s_plan_id.'&mem='.$memorize.'">'.$memorize.'</a></li></ul></th></tr>';
                }
            ?>
            <tr>
                <th>
                    <p>已完成的弟兄姐妹</p>
                </th>
            </tr>
            <tr>
                <td>
                    <div class="w3-bar-block w3-light-grey w3-cell" style="width:80px">
                        <button class="w3-bar-item w3-button tablink w3-grey" onclick="openChecked(event, 'all')">全部</button>
                        <button class="w3-bar-item w3-button tablink" onclick="openChecked(event, 'smallgroup')">本小组</button>
                    </div>
                    <div class = "w3-cell" style="margin-left:130px">
                        <div id="smallgroup" class="w3-container checked" style="display:none">
<<<<<<< ours
                            <?php 
=======
                            <?php
>>>>>>> theirs
                                foreach($checked_group as $b_group){
                                        $printname_group = $b_group['fname']." ". $b_group['lname']."; ";
                                        echo h($printname_group);
                                        }
                                if ($a_group == 0 && $n > 0){
                                    echo "暂无，赶紧读经成为第一个吧~";
                                }elseif ($n == 0){
                                    echo "本日无计划，但还是要好好过属灵生活哦~";
                                }
                            ?>                            
                        </div>
                        <div id="all" class="w3-container checked" style="display:block">
<<<<<<< ours
                            <?php 
=======
                            <?php
>>>>>>> theirs
                                foreach($checked as $b){
                                        $printname = $b['fname']." ". $b['lname']."; ";
                                        echo h($printname);
                                        }
                                if ($a == 0 && $n > 0){
                                    echo "暂无，赶紧读经成为第一个吧~";
                                }elseif ($n == 0){
                                    echo "本日无计划，但还是要好好过属灵生活哦~";
                                }
                            ?>      
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
<<<<<<< ours
                    <?php 
=======
                    <?php
>>>>>>> theirs
                        if ($result){
                            if ($bl == 1){
                                echo "您已完成该日计划，请再接再厉";
                            }else{
                            ?>
<<<<<<< ours
                                <form method="post" action="<?php  echo "checkin.php?page=".$dif_date."&planid=".$s_plan_id."&plan=".$ongoingplan_id ?>"> 
=======
                                <form method="post" action="checkin.php"> 
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="page" value="<?php echo (int) $dif_date; ?>">
                                <input type="hidden" name="planid" value="<?php echo (int) $s_plan_id; ?>">
                                <input type="hidden" name="plan" value="<?php echo (int) $ongoingplan_id; ?>">
>>>>>>> theirs
                                <input type="submit" name="submit" value="确认已读"> 
                                </form>
                        <?php }}
                        
                    ?>
                </th>
            </tr>
        </table>
<<<<<<< ours
        <p class = "w3-left w3-button w3-black w3-round w3-circle"><a href = "home_en.php?plan=<?php echo $ongoingplan_id; ?>">English</a></p>
=======
        <p class = "w3-left w3-button w3-black w3-round w3-circle""><a href = "home_en.php?plan=<?php echo $ongoingplan_id; ?>">English</a></p>
>>>>>>> theirs
        <div class = "w3-padding-16">
            <form method='post' action="" class = "w3-right" style="padding-top:6px">
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