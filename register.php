<?php
// Include database connection file
require('connect.php');

//set timezone
date_default_timezone_set("America/Los_Angeles");

//get small group name
$sql = "SELECT * FROM `smallgroup`";
$resultset = mysqli_query($connection, $sql) or die(mysqli_error());
$smallgroup = array();
while ($r = mysqli_fetch_assoc($resultset)){
    $smallgroup[] = $r;
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    //get fname and lname
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $s_group = $_POST["s_group"];
    
    //validate email
    $email = $_POST["email"];
    if (!empty($email)) {
        if ((!filter_var($email, FILTER_VALIDATE_EMAIL)) && (!empty($email))) {
        $email_err = "Invalid email format";
        }
    }
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO `users` (`username`, `password`, `email`, `fname`, `lname`, `smallgroup`) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_username, $param_password, $param_email, $fname, $lname, $s_group);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            // Creates a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
  <div class="container">
    <h1>Register</h1>
    <hr>
    
    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
      <label for="username"><b>*User Name</b></label>
      <input type="text" placeholder="Enter User Name" name="username" id="username" value="<?php echo $username; ?>" required>
      <span class="help-block"><?php echo $username_err; ?></span>
    </div>

    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
      <label for="password"><b>*Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" id="password" value="<?php echo $password; ?>" required>
      <span class="help-block"><?php echo $password_err; ?></span>
    </div>

    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
      <label for="confirm_password"><b>*Confirm Password</b></label>
      <input type="password" placeholder="Confirm Your Password" name="confirm_password" id="confirm_password" value="<?php echo $confirm_password; ?>" required>
      <span class="help-block"><?php echo $confirm_password_err; ?></span>
    </div>
    
    <div class="form-group">
      <label for="fname"><b>*First Name</b></label>
      <input type="text" placeholder="First Name" name="fname" id="fname" required>
    </div>
    
    <div class="form-group">
      <label for="lname"><b>*Last Name</b></label>
      <input type="text" placeholder="Last Name" name="lname" id="lname" required>
    </div>
    <div class="form-group">
        <label for="s_group"><b>*所在的小组(没有请选择其他)<br>Your small group(choose other if none):</b></label><br>
        <select name="s_group" id="s_group" required>
            <?
                foreach($smallgroup as $group){
                    echo '<option value='.$group[id].'>'.$group[name].'</option>';
                }

            ?>
        </select>
    </div>
      <br>
    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
      <label for="email"><b>Email</b></label>
      <input type="text" placeholder="Leave Blank if You Don't Have One!" name="email" id="email">
      <span class="help-block"><?php echo $email_err; ?></span>
    </div>
    
    <hr>
    <button type="submit" class="registerbtn">Register</button>
  </div>
  <div class="container signin">
    <p>Already have an account? <a href="login.php">Sign in</a>.</p>
  </div>
</form>
</body>
</html>