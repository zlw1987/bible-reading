<?php
// Initialize the session
session_start();
// Include database connection file
require('connect.php');
require_once 'vendor/autoload.php';

//set timezone
date_default_timezone_set("America/Los_Angeles");
 
// Check if the user is already logged in, if yes then redirect him to plan_page page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: plan_page.php");
    exit;
}else if(isset($_COOKIE['rememberme'])){
 
  // Decrypt cookie variable value
  $userid = decryptCookie($_COOKIE['rememberme']);
 
  $sql_query = "select count(*) as cntUser,id, username, password from users where id='".$userid."'";
  $result = mysqli_query($connection,$sql_query);
  $row = mysqli_fetch_array($result);

  $count = $row['cntUser'];
  $username = $row['username'];
  

  if( $count > 0 ){
    $_SESSION['userid'] = $userid; 
    $_SESSION["loggedin"] = true;
    $_SESSION["username"] = $username; 
    $_SESSION["fname"] = $fname;
    header('Location: plan_page.php');
    exit;
  }
}

// Encrypt cookie
function encryptCookie( $value ) {

   $key = hex2bin(openssl_random_pseudo_bytes(4));

   $cipher = "aes-256-cbc";
   $ivlen = openssl_cipher_iv_length($cipher);
   $iv = openssl_random_pseudo_bytes($ivlen);

   $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

   return( base64_encode($ciphertext . '::' . $iv. '::' .$key) );
}

// Decrypt cookie
function decryptCookie( $ciphertext ) {

   $cipher = "aes-256-cbc";

   list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));
   return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);

}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
 
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, fname FROM users WHERE username = ?";
            
        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $userid, $username, $hashed_password, $fname);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userid"] = $userid;
                            $_SESSION["username"] = $username; 
                            $_SESSION["fname"] = $fname;
                            if( isset($_POST['rememberme']) ){
    
                                // Set cookie variables
                                $days = 365;
                                $value = encryptCookie($userid);
                                setcookie ("rememberme",$value,time()+ ($days * 24 * 60 * 60 * 1000));
                            }
                            
                            // Redirect user to plan_page page
                            header("location: plan_page.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
    
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }    
    
    // Close connection
    mysqli_close($connection);
}
?>

<html>
<head>
  <link rel="stylesheet" href="mystyle.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <div class="imgcontainer">
    <img src="bible.jfif" alt="Bible" class="banner">
    <h2>Let's read bible together!</h2>
  </div>

  <div class="container">
    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
      <div class="row">
        <div class="col-25">
          <label for="username"><b>Username</b></label>
        </div>
        <div class="col-75">
          <input type="text" placeholder="Enter Username" name="username" value="<?php echo $username; ?>" required>
          <span class="help-block"><?php echo $username_err; ?></span>
        </div>
      </div>
    </div>
    
    
    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
      <div class="row">
        <div class="col-25">
          <label for="password"><b>Password</b></label>
        </div>
        <div class="col-75">
          <input type="password" placeholder="Enter Password" name="password" required>
          <span class="help-block"><?php echo $password_err; ?></span>
        </div>
      </div>
    </div>
    <div class="row">
      <button type="submit">Login</button>
    </div>
    <div class="row">
      <label>
        <input type="checkbox" checked="checked" name="rememberme"> Remember me
      </label>
    </div>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn" onclick="window.location = 'register.php'">Register</button>
  </div>
</form>
</body>
</html>