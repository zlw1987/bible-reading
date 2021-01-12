<?
require('judgelogin.php');
require('connect.php');
if ($_GET['plan']){
    $plan = $_GET['plan'];
    $_SESSION['plan'] = $_GET['plan'];
}elseif ($_SESSION['plan']){
    $plan = $_SESSION['plan'];
}else{
    header('Location: plan_page_en.php');
    exit;
}
$or_page = $_SESSION["page"];
$plan = $_SESSION['plan'];
$userid = $_SESSION["userid"];
$fname = $_SESSION["fname"];
$months = array("Januray","February","March","April","May","June","July","August","September","October","November","December");

//set timezone
date_default_timezone_set("America/Los_Angeles");
//get todate dates variables
$today_date = date('d');
$today_month = date('n');
$today_year = date('Y');
$today = getdate(date('U'));

//get current showing month and year and its pre and next
if ($_GET['year']){
    $cur_year = $_GET['year'];
}else{
    $cur_year = $today_year;
}
if ($_GET['month']){
    $cur_month = $_GET['month'];
}else{
    $cur_month = $today_month;
}
if ($cur_month == 1){
    $prev_month = 12;
    $prev_year = $cur_year - 1;
}else{
    $prev_month = $cur_month - 1;
    $prev_year = $cur_year;
}
if ($cur_month == 12){
    $next_month = 1;
    $next_year = $cur_year + 1;
}else{
    $next_month = $cur_month + 1;
    $next_year = $cur_year;
}
$cur_dom = date('t', mktime(0,0,0,$cur_month,01,$cur_year));


//calculate the days that need to be filled before 1st day of the month
$filled_days = date('w', mktime(0,0,0,$cur_month,01,$cur_year));
$prev_dom = date('t', mktime(0,0,0,$prev_month,01,$prev_year));

//get the startdate
$sql = "SELECT startdate FROM ongoingplan WHERE plan_id = $plan";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
while ($r = mysqli_fetch_assoc($resultset)){
    $startdate = $r[startdate];
} 

//get user checkedin dates and make dictionary for hash
$sql = "SELECT sp.day FROM s_checkins AS sc, s_plan AS sp WHERE sc.user_id = $userid AND sp.plan_id = $plan AND sc.s_plan_id = sp.id";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$n_days = mysqli_affected_rows($connection);
$checked_dates = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $checked_dates[$r[day]] = 1;
}   

//get hasplan dates
$sql = "SELECT day FROM s_plan WHERE plan_id = $plan";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$has_plan = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $has_plan[$r[day]] = 1;
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
    <br>
    <div class="container w3-padding-small w3-center">
        <table class = "w3-table w3-card-4 w3-border w3-bordered w3-centered">
            <tr>
                <th class = "w3-pale-red" colspan="7">
                    <p>Hi <?echo $fname;?>,<br>
                    <a href = "calendar_en.php?plan=<?echo $plan;?>">Today is <?echo  $today[month]." ". $today[mday].", ". $today[year].", ". $today[weekday];?></a>
                </th>
            <tr>
                <th  colspan="7" class = "w3-left-align">
                    <div class="w3-cell-row">
                        <div class = "w3-left-align w3-cell">
                            <a href = "calendar_en.php?year=<?php echo $prev_year;?>&month=<?php echo $prev_month; ?>&plan=<?echo $plan;?>">&lt;&lt;prev</a>
                        </div>
                        <div class = "w3-cell w3-center">
                            <?echo $months[$cur_month - 1]."&nbsp;".$cur_year?>
                        </div>
                        <div class = "w3-right-align w3-cell">
                            <a href = "calendar_en.php?year=<?php echo $next_year;?>&month=<?php echo $next_month; ?>&plan=<?echo $plan;?>">next>></a>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th>
                    Su
                </th>
                <th>
                    Mo
                </th>
                <th>
                    Tu
                </th>
                <th>
                    We
                </th>
                <th>
                    Th
                </th>
                <th>
                    Fr
                </th>
                <th>
                    Sa
                </th>
            </tr>
            <tr>
                <? 
                    $n = 0;
                    for ($i = $prev_dom - $filled_days + 1; $i <= $prev_dom; $i++){
                        $show_date = strval($prev_year)."-".strval($prev_month)."-".strval($i);
                        $date_diff = intval(ceil((strtotime($show_date) - strtotime($startdate)) / (60*60*24) + 1));
                        $page = intval(ceil((strtotime($show_date) - strtotime(date("Y-m-j"))) / (60*60*24)));
                        if (array_key_exists($date_diff, $has_plan)){
                            if (array_key_exists($date_diff, $checked_dates)) {
                                $color = 'w3-grey';
                            }else{
                                $color = 'w3-white';
                            }
                            $url = '<a href = "home_en.php?plan='.$plan.'&page='.-1*$page.'">';
                            if ($i == $today_date and $prev_year == $today_year and $prev_month == $today_month){
                                echo '<td class = "w3-border w3-border-color w3-text-grey '.$color.'">'.$url,$i.'</a></td>';
                            }else{
                                echo '<td class = "w3-text-grey '.$color.'">'.$url,$i.'</a></td>';
                            }
                        }else{
                            if ($i == $today_date and $prev_year == $today_year and $prev_month == $today_month){
                                echo '<td class = "w3-border w3-border-color w3-text-grey w3-light-grey">'.$i.'</td>';
                            }else{
                                echo '<td class = "w3-text-grey w3-light-grey">'.$i.'</td>';
                            }
                        }
                        $n++;
                    }
                    for ($i = 1;$i <=$cur_dom;$i++){
                        $show_date = strval($cur_year)."-".strval($cur_month)."-".strval($i);
                        $date_diff = intval(ceil((strtotime($show_date) - strtotime($startdate)) / (60*60*24) + 1));
                        $page = intval(ceil((strtotime($show_date) - strtotime(date("Y-m-j"))) / (60*60*24)));
                        if (array_key_exists($date_diff, $has_plan)){
                            if (array_key_exists($date_diff, $checked_dates)) {
                                $color = 'w3-grey';
                            }else{
                                $color = 'w3-white';
                            }
                            $url = '<a href = "home_en.php?plan='.$plan.'&page='.-1*$page.'">';
                            if ($i == $today_date and $cur_year == $today_year and $cur_month == $today_month){
                                echo '<td class = "w3-border w3-border-red '.$color.'">'.$url,$i.'</a></td>';
                            }else{
                                echo '<td class = '.$color.'>'.$url,$i.'</td>';
                            }
                        }else{
                            echo '<td class = "w3-light-grey">'.$i.'</td>';
                        }
                        $n++;
                        if ($n % 7 == 0){
                            echo "</tr><tr>";
                        }
                    }
                    $i = 1;
                    while ($n < 42){
                        $show_date = strval($next_year)."-".strval($next_month)."-".strval($i);
                        $date_diff = intval(ceil((strtotime($show_date) - strtotime($startdate)) / (60*60*24) + 1));
                        $page = intval(ceil((strtotime($show_date) - strtotime(date("Y-m-j"))) / (60*60*24)));
                        if (array_key_exists($date_diff, $has_plan)){
                            if (array_key_exists($date_diff, $checked_dates)) {
                                $color = 'w3-grey';
                            }else{
                                $color = 'w3-white';
                            }
                            $color = $color.$today_color;
                            $url = '<a href = "home_en.php?plan='.$plan.'&page='.-1*$page.'">';
                            if ($i == $today_date and $next_year == $today_year and $next_month == $today_month){
                                echo '<td class = "w3-text-grey w3-border w3-border-red '.$color.'">'.$url,$i.'</a></td>';
                            }else{
                                echo '<td class = "w3-text-grey '.$color.'">'.$url,$i.'</a></td>';
                            }
                        }else{
                            echo '<td class = "w3-light-grey w3-text-grey">'.$i.'</td>';
                        }
                        $n++;
                        $i++;
                        if ($n % 7 == 0){
                            echo "</tr><tr>";
                        }
                    }
                    echo "</tr>";
                ?>
            </tr>
                <th class = "w3-pale-red" colspan="7">
                    <span class="w3-tag w3-white">&nbsp;</span>&nbsp;Unread&nbsp;<span class="w3-tag w3-grey">&nbsp;</span>&nbsp;Read&nbsp;<span class="w3-tag w3-light-grey">&nbsp;</span>&nbsp;No Plan&nbsp;<span class="w3-tag w3-white w3-border w3-border-red">&nbsp;</span>&nbsp;Today&nbsp;
                            <br>Click dats to check
                </th>
            </tr>
        </table>
        <br>
        <div class="w3-cell-row">
            <div class = "w3-cell w3-center">
                <a class="w3-btn w3-black w3-round" href = "home_en.php?plan=<? echo $plan;?>&page=<?echo $or_page;?>">Go back</a>
            </div>
        </div>
    </div>
    <br>
</body>
</html>