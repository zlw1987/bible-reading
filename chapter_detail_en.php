<?
    session_start();
    $chapter = $_GET['chapter'];
    $book = $_GET['book'];
    $version = $_GET['version'];
    $book_url = 'https://www.o-bible.com/cgibin/ob.cgi?version='.$version.'&&book='.$book.'&chapter='.$chapter.'&p';
    $s_plan_id = $_GET['splanid'];
    
    //set dictionary for bible url
    require('translation_map.php');
    
    $plan = $_SESSION['plan'];
    $page = $_SESSION['page'];
    $back_url = "home_en.php?plan=".$plan."&page=".$page;
    
    $detail = $_SESSION['plandetail'];
    $n = count($detail);
    for ($i = 0; $i <= $n; $i++){
        $temp = explode(" ", $detail[$i]);
        $chapter_arr[$i] = $temp[1];
        $book_name = substr($temp[0],0,strlen($temp[0])-3); 
        $book_arr[$i] = $book_map[$book_name]; 
        if ($book_arr[$i] == $book and $chapter_arr[$i] == $chapter){
            $cur = $i;
        }
    }
    if ($cur > 0){
        $prev_url = '<a class="w3-button w3-round w3-orange" href = "chapter_detail_en.php?book='.$book_arr[$cur - 1].'&chapter='.$chapter_arr[$cur - 1].'&version='.$version.'&splanid='.$s_plan_id.'">Prev</a>';
    }else{
        $prev_url = '<div class = "w3-button w3-round">Start</div>';
    }
    if ($cur < $n -1){
        $next_url = '<a class="w3-button w3-round w3-orange" href = "chapter_detail_en.php?book='.$book_arr[$cur + 1].'&chapter='.$chapter_arr[$cur + 1].'&version='.$version.'&splanid='.$s_plan_id.'">Next</a>';
    }else{
        $next_url = '<a class="w3-button w3-round w3-orange" href = "comment_en.php?splanid='.$s_plan_id.'">Thoughts</a>';
    }
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
    <br><br><br>
    <div class = "container">
    <iframe id="preview-frame" src="<?php echo $book_url?>" name="preview-frame" frameborder="0" style="height:6700px;width:100%">
    <!--<iframe src="<?php echo $book_url?>" frameborder="0" style="height:100%;width:100%">-->
    </iframe>
    </div>
    <br>
    <div class = "w3-cell-row w3-top w3-light-grey" style="padding: 5px 5px 5px 5px">
        <div class = "w3-cell w3-left-align" style = "width:33%">
            <? echo $prev_url;?>
        </div>
        <div class = "w3-cell w3-center" style = "width:33%">
            <a class="w3-button w3-round w3-orange " href="<? echo $back_url ?>" />Go back</a>
        </div>
        <div class = "w3-cell w3-right-align" style = "width:33%">
            <?echo $next_url;?>
        </div>
    </div>
</body>
</html>