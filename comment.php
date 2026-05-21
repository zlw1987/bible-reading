<?php
require('judgelogin.php');

$userid = (int) $_SESSION["userid"];
$page = (int) ($_SESSION["page"] ?? 0);
$plan = (int) ($_SESSION['plan'] ?? 0);
$s_plan_id = input_int($_GET, 'splanid', 0);
if ($s_plan_id <= 0) {
    redirect_to('plan_page.php');
}
$back_url = "home.php?plan=" . $plan . "&page=" . $page;
date_default_timezone_set("America/Los_Angeles");

$row = db_one($connection, "SELECT smallgroup FROM users WHERE id = ? LIMIT 1", "i", $userid);
$smallgroup = $row ? (int) $row['smallgroup'] : 0;

$rows = db_all(
    $connection,
    "SELECT comments.*, users.fname, users.lname, users.smallgroup, users.avatar
     FROM comments
     INNER JOIN users ON comments.user_id = users.id
     WHERE comments.s_plan_id = ?
     ORDER BY comments.time ASC",
    "i",
    $s_plan_id
);

$all_comments = array();
$all_reply = array();
foreach ($rows as $r) {
    if ((int) $r['p_id'] === 0) {
        $all_comments[] = $r;
    } else {
        $all_reply[(int) $r['p_id']][] = $r;
    }
}

mysqli_close($connection);
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">
</head>

<body class = "w3-light-grey">
    <div class="w3-padding" style = "height:81%;overflow: scroll;">
            <ul class="w3-ul w3-hoverable" style="width:100%" >
                <?php foreach ($all_comments as $gc): ?>
                    <li class="w3-card w3-round-xlarge w3-white" style="margin-bottom:5px">
                        <div class="w3-cell-row">
                            <div class="w3-cell w3-left"><?php echo h($gc['fname'] . ' ' . $gc['lname']); ?></div>
                            <div class="w3-cell w3-right w3-text-grey">@<?php echo h($gc['time']); ?></div>
                        </div>
                        <div class="w3-cell-row"><?php echo nl2br(h($gc['detail'])); ?>
                        <?php if ((int) $gc['user_id'] === $userid): ?>
                            <form class="w3-right" action="delete_comment.php" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="plan" value="<?php echo $s_plan_id; ?>">
                                <input type="hidden" name="id" value="<?php echo (int) $gc['id']; ?>">
                                <button type="submit" class="w3-button w3-padding-small">删除</button>
                            </form>
                        <?php else: ?>
                            <a href="#" onclick="myReply(<?php echo h(json_encode($gc['fname'])); ?>, <?php echo h(json_encode($gc['lname'])); ?>, <?php echo (int) $gc['id']; ?>)" class="w3-right">回复</a>
                        <?php endif; ?>
                        </div>
                        <div class="w3-padding">
                            <?php foreach (($all_reply[(int) $gc['id']] ?? array()) as $gr): ?>
                                <div class="w3-cell-row">
                                    <div class="w3-cell w3-left"><?php echo h($gr['fname'] . ' ' . $gr['lname']); ?></div>
                                    <div class="w3-cell w3-right w3-text-grey">@<?php echo h($gr['time']); ?></div>
                                </div>
                                <div class="w3-cell-row"><?php echo nl2br(h($gr['detail'])); ?>
                                <?php if ((int) $gr['user_id'] === $userid): ?>
                                    <form class="w3-right" action="delete_comment.php" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="plan" value="<?php echo $s_plan_id; ?>">
                                        <input type="hidden" name="id" value="<?php echo (int) $gr['id']; ?>">
                                        <button type="submit" class="w3-button w3-padding-small">删除</button>
                                    </form>
                                <?php else: ?>
                                    <a href="#" onclick="myReply(<?php echo h(json_encode($gr['fname'])); ?>, <?php echo h(json_encode($gr['lname'])); ?>, <?php echo (int) $gc['id']; ?>)" class="w3-right">回复</a>
                                <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
                <script>
                    function myReply(a,b,c) {
                        document.getElementById("myTextarea").focus();
                        document.getElementById('myTextarea').value = "回复 "+a+" "+b+": ";
                        document.getElementById('note').style.display='none';
                        document.getElementById('pid').value = c;
                    }
                </script>
            </ul>
    </div>
    <div class = "w3-white w3-card-4 w3-bottom">
        <div class = "w3-padding-large">
        <form action="insert_comment.php?plan=<?php echo $s_plan_id; ?>" id="comment" method="post">
            <?php echo csrf_field(); ?>
            <textarea id="myTextarea" style = "width:100%;" name="comment" form="comment" onfocus="document.getElementById('note').style.display='none'" onblur="if(value=='')document.getElementById('note').style.display='block'" required></textarea>
            <input type="hidden" id="pid" name="pid" value="0">
            <div id="note" class="note w3-display-topleft w3-padding-large" onclick = "myComment()">
                <font color="#777">&nbsp;跟大家分享今日的灵修心得吧...</font>
            </div>
            <script>
                function myComment() {
                    document.getElementById('note').style.display='none';
                    document.getElementById("myTextarea").focus();
                }
            </script>
            <a class="w3-button w3-small w3-round w3-black" style="margin: 10px 0px 0px 0px" href="<?php echo h($back_url); ?>">返回</a>
            <input class = "w3-right" style="margin: 14px 0px 0px 0px" type="submit" value = "提交" onclick = "this.disabled=true; this.value='Sending…'; this.form.submit();">
        </form>
        </div>
    </div>
</body>
</html>
