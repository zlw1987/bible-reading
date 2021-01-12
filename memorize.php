<?
    session_start();
    
    //set dictionary for bible url
    require('translation_map.php');
    $version = "hgb";
    $memorize = $_GET['mem'];
    $sp1 = explode(" ", $memorize);
    $book_name = $sp1[0];
    $book = $book_map[$book_name]; 
    $sp2 = explode(":", $sp1[1]);
    $chapter = $sp2[0];
    $verse = $sp2[1];
    //$book_url = 'https://www.o-bible.com/cgibin/ob.cgi?version='.$version.'&book='.$book.'&chapter='.$chapter.'&p';
    $book_url = 'https://springbible.fhl.net/Bible2/cgic201/read001.cgi?len=1&s=1&ft=15&na='.$book.'&ch='.$chapter.'&v='.$verse;
    $s_plan_id = $_GET['splanid'];
    
    $plan = $_SESSION['plan'];
    $page = $_SESSION['page'];
    $back_url = "home.php?plan=".$plan."&page=".$page;
    
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
    <div class = "container">
    <iframe id="preview-frame" src="<?php echo $book_url?>" name="preview-frame" frameborder="0" style="height:6700px;width:100%;">
    <!--<iframe src="<?php echo $book_url?>" frameborder="0" style="height:100%;width:100%">-->
    </iframe>
    </div>
    <br>
    <div class = "w3-cell-row w3-top w3-light-grey" style="padding: 5px 5px 10px 5px">
        <div class = "w3-cell w3-center">
            <? echo '<h3>背诵经文</h3>';?>
            <a class="w3-button w3-round w3-orange " href="<? echo $back_url ?>" />返回</a>
        </div>
    </div>
</body>
</html>