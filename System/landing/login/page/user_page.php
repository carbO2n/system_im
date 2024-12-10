<?php
session_start();
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

include 'sm_db.php'; // Ensure the database connection is included

// Retrieve user and student details using the logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch user details from the 'user' table
$user_query = "SELECT * FROM user WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Fetch student details from the 'students' table
$student_query = "SELECT * FROM students WHERE user_id = '$user_id'";
$student_result = mysqli_query($conn, $student_query);
$student_data = mysqli_fetch_assoc($student_result);

// Set a default profile picture if none is available
$profile_pic = (!empty($student_data['profile_pic'])) ? 'uploads/' . htmlspecialchars($student_data['profile_pic']) : 'uploads/default.jpg';

// Handle profile picture upload
if (isset($_POST['upload_pic'])) {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_name = $_FILES['profile_pic']['name'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid() . '.' . $file_ext; // Generate unique file name
            $upload_dir = 'uploads/';
            $upload_path = $upload_dir . $new_name;

            // Move uploaded file to the upload directory
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Update the database with the new image path
                $update_query = "UPDATE students SET profile_pic = '$new_name' WHERE user_id = '$user_id'";
                mysqli_query($conn, $update_query);
                
                // Redirect to refresh the page and show the new image
                header('location: user_page.php');
                exit();
            } else {
                echo "Error moving the uploaded file!";
            }
        } else {
            echo "Invalid file type! Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "Error uploading the file!";
    }
}

// Handle profile data update
if (isset($_POST['update_profile'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $new_dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $new_course = mysqli_real_escape_string($conn, $_POST['course']);
    $new_block_year = mysqli_real_escape_string($conn, $_POST['block_year']);
    $new_allowance = mysqli_real_escape_string($conn, $_POST['allowance']);
    
    // Update the database with the new profile data
    $update_query = "UPDATE user SET name = '$new_name', email = '$new_email' WHERE id = '$user_id'";
    mysqli_query($conn, $update_query);

    $update_student_query = "UPDATE students SET mobile = '$new_mobile', dob = '$new_dob', course = '$new_course', block_year = '$new_block_year', allowance = '$new_allowance' WHERE user_id = '$user_id'";
    mysqli_query($conn, $update_student_query);

    // Redirect to the updated profile page
    header('location: user_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="css/user_page.css">
</head>
<body>
    <?php include 'nav.php'; ?> <!-- Include navigation bar -->

    <div class="profile-container">
        <div class="profile-header">
            <h2>Student Profile</h2>
            <!-- Display profile picture -->
            <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" style="width:150px; height:150px; border-radius:50%; object-fit:cover;">
            <a href="?edit=true" class="form-btn">Edit Profile</a>
        </div>

        <!-- Display Profile Data -->
        <?php if (isset($_GET['edit']) && $_GET['edit'] == 'true'): ?>
            <!-- Editable form when 'Edit' button is clicked -->
            <form action="" method="post">
                <input type="text" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                <input type="text" name="mobile" value="<?php echo htmlspecialchars($student_data['mobile']); ?>" required>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($student_data['dob']); ?>" required>
                <input type="text" name="course" value="<?php echo htmlspecialchars($student_data['course']); ?>" required>
                <input type="text" name="block_year" value="<?php echo htmlspecialchars($student_data['block_year']); ?>" required>
                <input type="number" name="allowance" value="<?php echo htmlspecialchars($student_data['allowance']); ?>" required>
                <input type="submit" name="update_profile" value="Save Changes" class="form-btn">
            </form>

            <!-- File upload form -->
            <form action="" method="post" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="profile_pic" accept="image/*" required>
                <input type="submit" name="upload_pic" value="Upload Profile Picture" class="form-btn">
            </form>
        <?php else: ?>
            <!-- Display non-editable profile data -->
            <ul class="profile-details">
                <li><strong>Name:</strong> <?php echo htmlspecialchars($user_data['name']); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></li>
                <li><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student_data['dob']); ?></li>
                <li><strong>Mobile Number:</strong> <?php echo htmlspecialchars($student_data['mobile']); ?></li>
                <li><strong>Course:</strong> <?php echo htmlspecialchars($student_data['course']); ?></li>
                <li><strong>Block and Year:</strong> <?php echo htmlspecialchars($student_data['block_year']); ?></li>
                <li><strong>Allowance:</strong> <?php echo htmlspecialchars($student_data['allowance']); ?></li>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
