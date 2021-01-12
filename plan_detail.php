<?
  $detail = $_GET['plan'];
  $name = $_GET['name'];

  

?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="w3.css">
    </head>
<body>
    <br>
    <div class="w3-container">
        <h2 w3-center><?echo $name;?></h2>
        <?echo $detail;?>
        <br>
        <button class="w3-button w3-round w3-large w3-black" style="margin: 12px 0px 12px 0px" onclick="javascript:history.back(1)" />返回</button>
        
    </div>
<br>
<br>
</body>
</html>