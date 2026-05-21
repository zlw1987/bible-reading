<?php
session_start();
include "connect.php";
require_once "security.php";
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
<<<<<<< ours

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
=======
>>>>>>> theirs

    $row = db_one($connection, "SELECT id, username, fname FROM users WHERE id = ? LIMIT 1", "i", $userid);

    if (!$row) {
        header('Location: login.php');
        exit;
    }

    $_SESSION['username'] = $row['username'];
    $_SESSION['fname'] = $row['fname'];
}
function decryptCookie($ciphertext) {
    $decoded = base64_decode((string) $ciphertext, true);
    if ($decoded === false) {
        return false;
    }

    $parts = explode('::', $decoded);
    if (count($parts) !== 3) {
        return false;
    }

    list($encrypted_data, $iv, $key) = $parts;
    return openssl_decrypt($encrypted_data, "aes-256-cbc", $key, 0, $iv);
}

// logout
if(isset($_POST['but_logout'])){
    verify_csrf();
    session_destroy();

    // Remove cookie variables
    setcookie("rememberme", "", array(
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ));

    header('Location: login.php');
    exit;
}
?>
