<?
require('judgelogin.php');
require('connect.php');
$userid = $_SESSION["userid"];
$fname = $_SESSION["fname"];

//get user signed up plans
$sql = "SELECT op.id, plan.name_en as name, plan.description_en as description, op.startdate FROM ongoingplan_user AS ou, ongoingplan AS op, plan WHERE ou.user_id = $userid AND ou.ongoingplan_id = op.id AND op.plan_id = plan.id";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$n_signup = mysqli_affected_rows($connection);
$signedUp = array();
$signedup_id = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $signedUp[] = $r;
    $signedup_id[] = $r[id];
}    


//get other plans
if ($n_signup > 0){
    $signedup_plan = join(",", $signedup_id);
    $other = array();
    $sql = "SELECT op.id, plan.name, plan.description, op.startdate FROM ongoingplan AS op, plan WHERE op.plan_id = plan.id AND op.plan_id NOT IN ($signedup_plan)";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
    while ($r = mysqli_fetch_assoc($resultset)){
        $other[] = $r;
    }
}else{
    $other = array();
    $sql = "SELECT op.id, plan.name, plan.description, op.startdate FROM ongoingplan AS op, plan WHERE op.plan_id = plan.id";
    $resultset = mysqli_query($connection, $sql) or die(mysqli_error());
    while ($r = mysqli_fetch_assoc($resultset)){
        $other[] = $r;
    }
}
$n_other = count($other);
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
    <div class="w3-container">
        <table class="w3-table w3-border w3-striped">
            <tr class = "w3-cell-row">
                <th class = "w3-cell"><p>Hi <?echo $fname;?>,<br></th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page.php">中文版</a></th>
            <tr>
                <th><p>My plans</p></th>
            </tr>
            <tr>
                <td>
                    <?
                        $i = 1;
                        if ($n_signup > 0){
                            echo '<ul class="w3-ul w3-card-4 w3-hoverable" style="width:100%" >';
                            foreach ($signedUp as $sp){
                                echo '<li>'.$i.'. '.'<a href = "home_en.php?plan='.$sp[id].'">'.$sp[name].'(Click to Enther)</a><br>&nbsp;&nbsp;&nbsp;Started on&nbsp;'.$sp[startdate].' <br>&nbsp;&nbsp;&nbsp;<a href = "plan_detail.php?name='.$sp[name].'&plan='.$sp[description].'">Plan Description</a></li>';
                                $i = $i + 1;
                            }
                            echo '</ul>';
                        }else{
                            echo "&nbsp;&nbsp;Sign up for one!";
                        }
                    ?>
                    
                </td>
            </tr>
            <tr>
                <th><p>Other Plans</p></th>
            </tr>
            <tr>
                <td>
                    <ul class="w3-ul w3-card-4 w3-hoverable" style="width:100%" >
                    <?
                        $i = 1;
                        if ($n_other > 0){
                            foreach ($other as $o){
                                echo '<li>'.$i.'. '.'<a href = "plan_detail.php?name='.$o[name].'&plan='.$o[description].'">'.$o[name].'</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$o[startdate].' 开始<br>&nbsp;&nbsp;&nbsp;<a href = "signup_plan.php?plan='.$o[id].'&user='.$userid.'">Sign up</a></li>';
                                $i = $i + 1;
                            }
                        }else{
                            echo "&nbsp;&nbsp;No more plans";
                        }
                    ?>
                    </ul>
                </td>
            </tr>
            <tr class="w3-light-grey">
                <th>Suggested Bible Reading Method</th>
            </tr>
            <tr>
                <td>
                    <p>1. Set aside half an hour in the early morning of each day, and read carefully two or three times according to the specified New Testament scriptures. <br>2. First, prepare your "spirit", calm your "heart" through prayer, remember that you are here in front of this loving Father and read His own words, so you can look to Him to teach you How to read. <br>3. After you have prepared your spirit, you can open the Bible and read the chapter that should be read slowly and carefully two or three times. It is better to read slowly rather than fast. <br>4. When reading, you can count yourself as the person who wrote the Bible, or the person being discussed. For example, you can count yourself as a listener when you praise the Lord's teachings; when you read a letter, you can count yourself as a letter writer; when you encounter a place to remember, you can count yourself as a party. In short, you must transfer yourself to the Bible, and let the Holy Spirit use the Bible to lead you to God to admire Christ and let Him enlighten you. <br>5. After reading the entire chapter two or three times, you can repeat the passage that you can touch yourself or the passage that you don’t understand and hope to understand. At this time, you can add meditation, prayer, or hymns... and turn these verses into your praise, wish, confession, or dedication to communicate with God. <br>6. If you really can’t find a scripture that can be read several times, you might as well find out the verses you recite on that day, read this verse several times and turn it into meditation and prayer You are moisturized and fed. <br>7. Leave the last five minutes of the day or find another time to think about where in your life you can use today's bible reading. <br></p>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <br>
</body>
</html>