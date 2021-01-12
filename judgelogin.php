<?php
session_start();
include "connect.php";
// Check user login or not
if(!$_SESSION['username']){
    header('Location: login.php');
}else{
    if(!isset($_COOKIE['rememberme'])){
        header('Location: login.php');
    }else{
        $userid = decryptCookie($_COOKIE['rememberme']);
 
        $sql_query = "select count(*) as cntUser,id, username, password, fname, lname from users where id='".$userid."'";
        $result = mysqli_query($connection,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];
        $username = $row['username'];
        $fname = $row['fname'];
        $_SESSION["fname"] = $fname;
  
        if( $count == 0 ){
            header('Location: login.php');
        }
    }
}
function decryptCookie( $ciphertext ) {

   $cipher = "aes-256-cbc";

   list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));
   return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);

}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();

    // Remove cookie variables
    $days = 365;
    setcookie ("rememberme","", time() - ($days * 24 * 60 * 60 * 1000));

    header('Location: login.php');
    exit;
}
?>