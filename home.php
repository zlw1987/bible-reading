<?
require('judgelogin.php');
$username = $_SESSION["username"];
$userid = $_SESSION["userid"];
$fname = $_SESSION["fname"];
if ($_GET['plan']){
    $ongoingplan_id = $_GET['plan'];
    $_SESSION['plan'] =  $_GET['plan'];
}elseif ($_SESSION['plan']){
    $ongoingplan_id = $_SESSION['plan'];
}else{
    header('Location: plan_page.php');
    exit;
}

require_once 'vendor/autoload.php';
//set time
date_default_timezone_set("America/Los_Angeles");
$mydate=getdate(date("U"));
$month = date(m);



//set dictionary for bible url
require('translation_map.php');

//get user small group
$sql = "SELECT smallgroup FROM users WHERE id = $userid";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$results = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $results[] = $r;
}
$smallgroup = $results[0][smallgroup];

//check which day should be showing
if ($_GET['page']){
    $dif_date = $_GET['page'];
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
$sql = "SELECT startdate,plan_id FROM ongoingplan WHERE id = $ongoingplan_id";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$results = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $results[] = $r;
}
$startdate = $results[0][startdate];
$plan_id = $results[0][plan_id];
$today = date("Y-m-j",strtotime("-$dif_date day"));
$day = ceil((strtotime($today) - strtotime($startdate)) / (60*60*24)) + 1;

//get the daily plan detail
$sql = "SELECT * FROM `s_plan` WHERE day = $day AND plan_id = $plan_id";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$result = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $result[] = $r;
}
$n = count($result);
if ($n > 0){
    $detail = $result[0][detail];
    $plandetail = explode("，", $detail);
    $memorize = $result[0][memorize];
}else{
    $plandetail = array("今天没有读经计划 自己好好安排时间吧");
}
$_SESSION['plandetail'] = $plandetail;

//get all checked in users
if ($result){
    
    $s_plan_id = $result[0][id];
    $sql = "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r, users AS z WHERE r.s_plan_id = $s_plan_id AND r.user_id = z.id";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
    $checked = array();
    //check if user has checked in
    $bl = 0;
    while ($r = mysqli_fetch_assoc($resultset)){
        $checked[] = $r;
        if ($r[id] == $userid){
            $bl = 1;
        }
    }
    $a = count($checked);
}

//get checked in users in the same small group as user
if ($result){
    $s_plan_id = $result[0][id];
    $sql = "SELECT z.fname, z.lname, z.avatar, z.id FROM s_checkins AS r, users AS z WHERE r.s_plan_id = $s_plan_id AND r.user_id = z.id AND z.smallgroup = $smallgroup";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
    $checked_group = array();
    //check if user has checked in
    while ($r = mysqli_fetch_assoc($resultset)){
        $checked_group[] = $r;
    }
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
                <th><p><?echo $fname;?> 您好,<br>
                    今天是 <?echo  $mydate[month]." ". $mydate[mday].", ". $mydate[year].", ". $mydate[weekday];?></p>
                    <div class="w3-cell-row">
                        <div class = "w3-left-align w3-cell">
                            <a href="<?php echo "home.php?page=".$pre."&plan=".$ongoingplan_id ?>">前一天</a>&nbsp;
                            <a href="<?php echo "home.php?plan=".$ongoingplan_id ?>">回到今天</a>&nbsp;
                            <a href="<?php echo "home.php?page=".$next."&plan=".$ongoingplan_id ?>">下一天</a>
                        </div>
                        <div class = "w3-right-align w3-cell">
                            <a class="w3-btn w3-black w3-round w3-padding-small" href="calendar.php?plan=<?echo $ongoingplan_id ?>">查看读经日历</a>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th><p><?echo $planday?></p></th>
            </tr>
            <tr>
                <th>
                <ul class="w3-ul w3-card-4 w3-hoverable" style="width:max-content" >
                <?
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
            <?
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
                            <?
                                foreach($checked_group as $b_group){
                                        $printname_group = $b_group[fname]." ". $b_group[lname]."; ";
                                        echo $printname_group;
                                        }
                                if ($a_group == 0 && $n > 0){
                                    echo "暂无，赶紧读经成为第一个吧~";
                                }elseif ($n == 0){
                                    echo "本日无计划，但还是要好好过属灵生活哦~";
                                }
                            ?>                            
                        </div>
                        <div id="all" class="w3-container checked" style="display:block">
                            <?
                                foreach($checked as $b){
                                        $printname = $b[fname]." ". $b[lname]."; ";
                                        echo $printname;
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
                    <?
                        if ($result){
                            if ($bl == 1){
                                echo "您已完成该日计划，请再接再厉";
                            }else{
                            ?>
                                <form method="post" action="<? echo "checkin.php?page=".$dif_date."&planid=".$s_plan_id."&plan=".$ongoingplan_id ?>"> 
                                <input type="submit" name="submit" value="确认已读"> 
                                </form>
                        <?}}
                        
                    ?>
                </th>
            </tr>
        </table>
        <p class = "w3-left w3-button w3-black w3-round w3-circle""><a href = "home_en.php?plan=<? echo $ongoingplan_id; ?>">English</a></p>
        <div class = "w3-padding-16">
            <form method='post' action="" class = "w3-right" style="padding-top:6px">
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