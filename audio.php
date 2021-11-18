<?
    session_start();
    $chapter = $_GET['chapter'];
    $book = $_GET['book'];
    $book_url = 'https://www.biblegateway.com/audio/biblica/ccb/'.$book.'.'.$chapter.'?interface=amp';
    $s_plan_id = $_GET['splanid'];
    
    //set dictionary for bible url
    require('translation_map.php');
    
    $plan = $_SESSION['plan'];
    $page = $_SESSION['page'];
    $back_url = "home.php?plan=".$plan."&page=".$page;
    
    $detail = $_SESSION['plandetail'];
    $n = count($detail);
    for ($i = 0; $i <= $n; $i++){
        $temp = explode(" ", $detail[$i]);
        $chapter_arr[$i] = $temp[1];
        $book_name = substr($temp[0],0,strlen($temp[0])-3); 
        $book_arr[$i] = $audio_map[$book_name];
        if ($book_arr[$i] == $book and $chapter_arr[$i] == $chapter){
            $cur = $i;
        }
    }
    if ($cur > 0){
        $prev_url = '<a class="w3-button w3-round w3-orange" href = "audio.php?book='.$book_arr[$cur - 1].'&chapter='.$chapter_arr[$cur - 1].'&splanid='.$s_plan_id.'">前一章</a>';
    }else{
        $prev_url = '<div class = "w3-button w3-round">从这里开始</div>';
    }
    if ($cur < $n -1){
        $next_url = '<a class="w3-button w3-round w3-orange" href = "audio.php?book='.$book_arr[$cur + 1].'&chapter='.$chapter_arr[$cur + 1].'&splanid='.$s_plan_id.'">后一章</a>';
    }else{
        $next_url = '<a class="w3-button w3-round w3-orange" href = "comment.php?splanid='.$s_plan_id.'">每日灵修分享</a>';
    }
    
// Close connection
mysqli_close($connection);
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="w3.css">


</head>
<body style = "background: #bf300d;">
    <br><br>
    <div class = "container">
    <iframe id="preview-frame" src="<? echo $book_url?>" name="preview-frame" frameborder="0" style="height:50%;width:100%;">
    <!--<iframe src="<? echo $book_url?>" frameborder="0" style="height:100%;width:100%">-->
    </iframe>
    </div>
    <br>
    <div class = "w3-cell-row w3-top w3-light-grey" style="padding: 2% 5px 2% 5px">
        <div class = "w3-cell w3-left-align" style = "width:33%">
            <? echo $prev_url;?>
        </div>
        <div class = "w3-cell w3-center" style = "width:33%">
            <a class="w3-button w3-round w3-orange " href="<? echo $back_url ?>" />返回</a>
        </div>
        <div class = "w3-cell w3-right-align" style = "width:33%">
            <?echo $next_url;?>
        </div>
    </div>
</body>
</html>