<?php
require('judgelogin.php');
require('connect.php');
<<<<<<< ours
=======
require_once 'security.php';
>>>>>>> theirs

$userid = (int) $_SESSION['userid'];
$fname = $_SESSION['fname'];

<<<<<<< ours
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

=======
>>>>>>> theirs
// Get user signed-up plans.
$signedUp = array();
$signedupPlanIds = array();

$signedSql = "
    SELECT op.id, plan.name, plan.description, op.startdate
    FROM ongoingplan_user AS ou
    INNER JOIN ongoingplan AS op ON ou.ongoingplan_id = op.id
    INNER JOIN plan ON op.plan_id = plan.id
    WHERE ou.user_id = ?
";
$signedStmt = mysqli_prepare($connection, $signedSql);
mysqli_stmt_bind_param($signedStmt, "i", $userid);
mysqli_stmt_execute($signedStmt);
$signedResult = mysqli_stmt_get_result($signedStmt);
while ($r = mysqli_fetch_assoc($signedResult)) {
    $signedUp[] = $r;
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
    $signedup_id[] = $r['id'];
}
=======
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
    $signedupPlanIds[] = (int) $r['id'];
}
mysqli_stmt_close($signedStmt);
$n_signup = count($signedUp);
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs

// Get other plans.
$other = array();
if ($n_signup > 0) {
    $placeholders = implode(',', array_fill(0, count($signedupPlanIds), '?'));
    $otherSql = "
        SELECT op.id, plan.name, plan.description, op.startdate
        FROM ongoingplan AS op
        INNER JOIN plan ON op.plan_id = plan.id
        WHERE op.id NOT IN ($placeholders)
    ";
    $otherStmt = mysqli_prepare($connection, $otherSql);
    $types = str_repeat('i', count($signedupPlanIds));
    mysqli_stmt_bind_param($otherStmt, $types, ...$signedupPlanIds);
    mysqli_stmt_execute($otherStmt);
    $otherResult = mysqli_stmt_get_result($otherStmt);
} else {
    $otherSql = "
        SELECT op.id, plan.name, plan.description, op.startdate
        FROM ongoingplan AS op
        INNER JOIN plan ON op.plan_id = plan.id
    ";
    $otherStmt = mysqli_prepare($connection, $otherSql);
    mysqli_stmt_execute($otherStmt);
    $otherResult = mysqli_stmt_get_result($otherStmt);
}

while ($r = mysqli_fetch_assoc($otherResult)) {
    $other[] = $r;
}
mysqli_stmt_close($otherStmt);
$n_other = count($other);

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
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
                <th class = "w3-cell"><p><?php echo $fname;?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
=======
                <th class = "w3-cell"><p><?php echo e($fname); ?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
>>>>>>> theirs
=======
                <th class = "w3-cell"><p><?php echo e($fname); ?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
>>>>>>> theirs
=======
                <th class = "w3-cell"><p><?php echo e($fname); ?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
>>>>>>> theirs
=======
                <th class = "w3-cell"><p><?php echo e($fname); ?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
>>>>>>> theirs
=======
                <th class = "w3-cell"><p><?php echo e($fname); ?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
>>>>>>> theirs
=======
                <th class = "w3-cell"><p><?php echo h($fname); ?> 您好,</th><th><a class = "w3-btn w3-black w3-round w3-padding-small w3-right" href = "plan_page_en.php">English Version</a></th>
>>>>>>> theirs
            </tr>
            <tr>
                <th><p>您加入的读经计划</p></th>
            </tr>
            <tr>
                <td>
                    <?php
                        $i = 1;
                        if ($n_signup > 0) {
                            echo '<ul class="w3-ul w3-card-4 w3-hoverable" style="width:100%" >';
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
                            foreach ($signedUp as $sp){
                                echo '<li>'.$i.'. '.'<a href = "home.php?plan='.$sp['id'].'">'.$sp['name'].'(点击进入)</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$sp['startdate'].' 开始<br>&nbsp;&nbsp;&nbsp;<a href = "plan_detail.php?name='.$sp['name'].'&plan='.$sp['description'].'">计划简介</a></li>';
                                $i = $i + 1;
=======
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
                            foreach ($signedUp as $sp) {
                                $id = (int) $sp['id'];
                                $name = e($sp['name']);
                                $description = e($sp['description']);
                                $startdate = e($sp['startdate']);
                                echo '<li>'.$i.'. '.'<a href="home.php?plan='.$id.'">'.$name.'(点击进入)</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$startdate.' 开始<br>&nbsp;&nbsp;&nbsp;<a href="plan_detail.php?name='.urlencode($name).'&plan='.urlencode($description).'">计划简介</a></li>';
                                $i++;
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
                            foreach ($signedUp as $sp) {
                                $id = (int) $sp['id'];
                                $name = h($sp['name']);
                                $description = h($sp['description']);
                                $startdate = h($sp['startdate']);
                                echo '<li>'.$i.'. '.'<a href="home.php?plan='.$id.'">'.$name.'(点击进入)</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$startdate.' 开始<br>&nbsp;&nbsp;&nbsp;<a href="plan_detail.php?name='.urlencode($name).'&plan='.urlencode($description).'">计划简介</a></li>';
                                $i++;
>>>>>>> theirs
                            }
                            echo '</ul>';
                        } else {
                            echo "&nbsp;&nbsp;您暂时没有加入任何计划";
                        }
                    ?>

                </td>
            </tr>
            <tr>
                <th><p>其他读经计划</p></th>
            </tr>
            <tr>
                <td>
                    <ul class="w3-ul w3-card-4 w3-hoverable" style="width:100%" >
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
                        <?php
                        $i = 1;
                        if ($n_other > 0){
                            foreach ($other as $o){
                                echo '<li>'.$i.'. '.'<a href = "plan_detail.php?name='.$o['name'].'&plan='.$o['description'].'">'.$o['name'].'</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$o['startdate'].' 开始<br>&nbsp;&nbsp;&nbsp;<a href = "signup_plan.php?plan='.$o['id'].'&user='.$userid.'">加入该计划</a></li>';
                                $i = $i + 1;
=======
                    <?php
                        $i = 1;
=======
                    <?php
                        $i = 1;
>>>>>>> theirs
=======
                    <?php
                        $i = 1;
>>>>>>> theirs
=======
                    <?php
                        $i = 1;
>>>>>>> theirs
=======
                    <?php
                        $i = 1;
>>>>>>> theirs
                        if ($n_other > 0) {
                            foreach ($other as $o) {
                                $id = (int) $o['id'];
                                $name = e($o['name']);
                                $description = e($o['description']);
                                $startdate = e($o['startdate']);
                                echo '<li>'.$i.'. '.'<a href="plan_detail.php?name='.urlencode($name).'&plan='.urlencode($description).'">'.$name.'</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$startdate.' 开始<br>&nbsp;&nbsp;&nbsp;<a href="signup_plan.php?plan='.$id.'">加入该计划</a></li>';
                                $i++;
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
<<<<<<< ours
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
>>>>>>> theirs
=======
                    <?php
                        $i = 1;
                        if ($n_other > 0) {
                            foreach ($other as $o) {
                                $id = (int) $o['id'];
                                $name = h($o['name']);
                                $description = h($o['description']);
                                $startdate = h($o['startdate']);
                                echo '<li>'.$i.'. '.'<a href="plan_detail.php?name='.urlencode($name).'&plan='.urlencode($description).'">'.$name.'</a><br>&nbsp;&nbsp;&nbsp;于&nbsp;'.$startdate.' 开始<br>&nbsp;&nbsp;&nbsp;<form action="signup_plan.php" method="post" style="display:inline">'.csrf_field().'<input type="hidden" name="plan" value="'.$id.'"><button type="submit" class="w3-button w3-padding-small">加入该计划</button></form></li>';
                                $i++;
>>>>>>> theirs
                            }
                        } else {
                            echo "&nbsp;&nbsp;暂时没有更多计划了";
                        }
                        ?>
                    </ul>
                </td>
            </tr>
            <tr class="w3-light-grey">
                <th>建议读经方法</th>
            </tr>
            <tr>
                <td>
                    <p>1. 每日清晨劃出半小時左右，按照所指定的應讀新約經文細細誦讀二遍或三遍。<br>2. 首先要準備你的「靈」，當藉禱告安靜你的「心」，記得你是在這位慈愛的父面前來讀祂親自默示出來的話，所以你可仰望祂教導你如何來讀。<br>3. 當你準備好你的靈以後，就可以打開聖經，把該讀的那章聖經慢慢的用心讀上二、三遍，讀時宜慢不宜快。<br>4. 讀時可把自己算作寫聖經的人，或被論及者。比如在讚主教訓時就可把自己算作當時聽道者；讀書信時，可把自己算作寫信者；遇到記事的地方，可把自己當作當事人。總之，要把自己調到聖經中去，且讓聖靈藉著聖經引你到 神面前去瞻仰基督，也讓祂來光照你。<br>5. 讀過二、三遍全章後，可把比較能摸到你自己的那一段或不了解又深盼明白的那一段經文再重複的多讀幾遍。這時你可以加上默想、禱告、或詩歌，……將這幾節聖經變成你的讚美、心願、認罪或奉獻，藉此與 神交通。<br>6. 若你真找不到可多讀幾遍的經文，不妨就把該日背誦經節找出，將此經節多讀幾遍，化成默想、禱告，這也能叫你得到滋潤與餵養。<br>7. 每日留下最後五分鐘或另找時間想想生活中有什麼地方可以用到今日讀經的得著。<br></p>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <br>
</body>
</html>
