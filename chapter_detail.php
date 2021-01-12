<?
    session_start();
    $chapter = $_GET['chapter'];
    $book = $_GET['book'];
    $version = $_GET['version'];
    //$book_url = 'https://www.o-bible.com/cgibin/ob.cgi?version='.$version.'&&book='.$book.'&chapter='.$chapter.'&p';
    $book_url = 'https://classic.biblegateway.com/passage/?search='.$book.'+'.$chapter.'&version=CUVS&interface=print';
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
        $book_arr[$i] = $book_map[$book_name]; 
        if ($book_arr[$i] == $book and $chapter_arr[$i] == $chapter){
            $cur = $i;
            if (count($temp) > 4){
                $verse = $temp[3]." ".$temp[4];
                $verses = explode("-",$temp[3]);
                $v_start = $verses[0];
                $v_length = $verses[1] - $verses[0] + 1;
                $book_url = 'https://springbible.fhl.net/Bible2/cgic201/read001.cgi?s=1&ft=15&na='.$book.'&ch='.$chapter.'&v='.$v_start.'&len='.$v_length;
            }
        }
    }
    if ($cur > 0){
        $prev_url = '<a class="w3-button w3-round w3-orange" href = "chapter_detail.php?book='.$book_arr[$cur - 1].'&chapter='.$chapter_arr[$cur - 1].'&version='.$version.'&splanid='.$s_plan_id.'">前一章</a>';
    }else{
        $prev_url = '<div class = "w3-button w3-round">从这里开始</div>';
    }
    if ($cur < $n-1){
        $next_url = '<a class="w3-button w3-round w3-orange" href = "chapter_detail.php?book='.$book_arr[$cur + 1].'&chapter='.$chapter_arr[$cur + 1].'&version='.$version.'&splanid='.$s_plan_id.'">后一章</a>';
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

    <!--<style>
      html body {width: 100%;height: 100%;padding: 0px;margin: 0px;overflow: scroll;font-family: arial;font-size: 10px;color: #6e6e6e;background:white;} #preview-frame {width: 100%;background-color: #fff;}
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>
         //function to fix height of iframe!
         var calcHeight = function() {
           //var headerDimensions = 0; //$('#header-bar').height();
           $('#preview-frame').height($(window).height());
         }
         
         $(document).ready(function() {
           calcHeight();
           /*$('#header-bar a.close').mouseover(function() {
             $('#header-bar a.close').addClass('activated');
           }).mouseout(function() {
             $('#header-bar a.close').removeClass('activated');
           });*/
         }); 
         
         $(window).resize(function() {
           calcHeight();
         }).load(function() {
           calcHeight();
         });
    </script>-->
</head>
<body>
    <?
        if ($verse){
            echo '<br><br>';

        }
    ?>
    <div class = "container">
            <iframe id="preview-frame" src="<?php echo $book_url?>" name="preview-frame" frameborder="0" style="height:100%;width:100%">
            <!--<iframe src="<?php echo $book_url?>" frameborder="0" style="height:100%;width:100%">-->
            </iframe>
        </div>
    <br>
    <div class = "w3-cell-row w3-top w3-light-grey" style="padding: 5% 5px 5% 5px">
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