<?php
session_start();
include "connect.php";
// Check user login or not
if (!isset($_SESSION['userid']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
} else {
    $userid = (int) $_SESSION['userid'];

    if (isset($_COOKIE['rememberme'])) {
        $cookieUserId = decryptCookie($_COOKIE['rememberme']);
        if (!ctype_digit((string) $cookieUserId) || (int) $cookieUserId !== $userid) {
            header('Location: login.php');
            exit;
        }
    }

    $sql_query = "SELECT id, username, fname FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($connection, $sql_query);
    mysqli_stmt_bind_param($stmt, "i", $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$row) {
        header('Location: login.php');
        exit;
    }

    $_SESSION['username'] = $row['username'];
    $_SESSION['fname'] = $row['fname'];
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
