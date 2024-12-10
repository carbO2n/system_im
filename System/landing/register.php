<?php
// Include database connection file
@include 'login/page/sm_db.php';

// Start session
session_start();

// Initialize error array
$errors = [];

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize inputs for the user table
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']); // Add mobile input

    // Sanitize inputs for the students table
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $block_year = mysqli_real_escape_string($conn, $_POST['block_year']);
    $allowance = mysqli_real_escape_string($conn, $_POST['allowance']);

    // Check if the email field is empty
    if (empty($email)) {
        $errors[] = 'Email is required!';
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format!';
    }

    // Check if mobile number is empty
    if (empty($mobile)) {
        $errors[] = 'Mobile number is required!';
    }

    // Check if passwords match
    if ($pass != $cpass) {
        $errors[] = 'Passwords do not match!';
    }

    // If there are no errors, proceed with database insertion
    if (empty($errors)) {
        // Check if email already exists in the user table
        $select = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($conn, $select);

        if (!$result) {
            $errors[] = 'Database query error: ' . mysqli_error($conn);
        } else {
            if (mysqli_num_rows($result) > 0) {
                $errors[] = 'User with this email already exists!';
            } else {
                // No hashing, store password as plain text
                $hashed_password = $pass; // Directly use the password as entered

                // Insert user into the user table
                $insert_user = "INSERT INTO user (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
                $insert_user_result = mysqli_query($conn, $insert_user);

                if ($insert_user_result) {
                    // Get the last inserted user ID
                    $user_id = mysqli_insert_id($conn);

                    // Insert student details into the students table (including mobile)
                    $insert_student = "INSERT INTO students (user_id, dob, course, block_year, allowance, mobile) 
                                       VALUES ('$user_id', '$dob', '$course', '$block_year', '$allowance', '$mobile')";
                    $insert_student_result = mysqli_query($conn, $insert_student);

                    if ($insert_student_result) {
                        // Redirect to login page after successful registration
                        header('location: login/login.php');
                        exit(); // Ensure script stops execution after redirection
                    } else {
                        $errors[] = 'Failed to insert student details: ' . mysqli_error($conn);
                    }
                } else {
                    $errors[] = 'Failed to insert user: ' . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="register.css">
    <style>
        body {
            background-image: url('login/Page/vtg pics/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Register An Account</h3>
            <?php
            // Display errors
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
            }
            ?>
            <input type="text" name="name" required placeholder="Enter your name">
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="text" name="mobile" required placeholder="Enter your mobile number"> <!-- Added mobile input -->
            <input type="date" name="dob" required placeholder="Enter your date of birth">
            <input type="text" name="course" required placeholder="Enter your course">
            <input type="text" name="block_year" required placeholder="Enter your block and year">
            <input type="number" name="allowance" required placeholder="Enter your allowance">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="password" name="cpassword" required placeholder="Confirm your password">
            <input type="submit" name="submit" value="Register Now" class="form-btn">
            <p>Already have an account? <a href="login/login.php">Login now</a></p>
        </form>
    </div>
</body>
</html>
