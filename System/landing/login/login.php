<?php
session_start();
@include 'page/sm_db.php';

// Check if the connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']); // Always escape user input

    // Fetch user details securely
    $select = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        
        // Verify plain-text passwords (for now, consider upgrading to password_hash)
        if ($pass === $row['password']) { // Replace with password_verify() if hashed
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_type'] = $row['user_type'];

            // Redirect based on user type
            if ($row['user_type'] == 'a') {
                header('location: page/admin_page.php');
            } elseif ($row['user_type'] == 'u') {
                header('location: page/user_page.php');
            }
            exit();
        } else {
            $error = 'Incorrect password!';
        }
    } else {
        $error = 'No user found with this email!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login Form</title>
   <link rel="stylesheet" href="login.css">
   <style>
      body {
         background-image: url('page/vtg pics/bg.jpg'); 
         background-size: cover;
         background-position: center;
         background-repeat: no-repeat;
      }
   </style>
</head>
<body>
<div class="nav">
   <div class="nav__logo"><a href="#">STUDENT MANAGEMENT SYSTEM</a></div>
</div>

<div class="form-container">
   <form action="" method="post">
      <h3>Login</h3>
      <?php if (isset($error)) : ?>
         <span class="error-msg"><?php echo $error; ?></span>
      <?php endif; ?>
      <input type="email" name="email" required placeholder="Enter your email">
      <input type="password" name="password" required placeholder="Enter your password">
      <input type="submit" name="submit" value="Login Now" class="form-btn">
      <p>Don't have an account? <a href="../register.php">Register</a></p>
   </form>
</div>
</body>
</html>
