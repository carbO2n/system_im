<?php
session_start();
include 'sm_db.php';

// Verify if the user is logged in and an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user details to verify role
$user_id = $_SESSION['user_id'];
$user_query = "SELECT user_type FROM user WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

if ($user_data['user_type'] != 'a') {
    // If not an admin, redirect to unauthorized access page or main page
    header('Location: unauthorized.php');
    exit();
}

// Get student ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_page.php');
    exit();
}

$student_id = intval($_GET['id']);

// Fetch student and user data
$query = "
    SELECT u.name, u.email, s.course, s.block_year, s.allowance, s.mobile 
    FROM students s
    JOIN user u ON u.id = s.user_id
    WHERE s.user_id = '$student_id'
";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: admin_page.php');
    exit();
}

$student = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $block_year = mysqli_real_escape_string($conn, $_POST['block_year']);
    $allowance = mysqli_real_escape_string($conn, $_POST['allowance']);

    // Update user table
    $update_user_query = "UPDATE user SET name = '$name', email = '$email' WHERE id = '$student_id'";
    mysqli_query($conn, $update_user_query);

    // Update students table
    $update_student_query = "
        UPDATE students 
        SET mobile = '$mobile', course = '$course', block_year = '$block_year', allowance = '$allowance' 
        WHERE user_id = '$student_id'
    ";
    mysqli_query($conn, $update_student_query);

    // Redirect back to admin page
    header('Location: admin_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/edit_student.css">
</head>
<body>
    <div class="edit-container">
        <h2>Edit Student</h2>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student['mobile']); ?>" required>

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>

            <label for="block_year">Block/Year:</label>
            <input type="text" id="block_year" name="block_year" value="<?php echo htmlspecialchars($student['block_year']); ?>" required>

            <label for="allowance">Allowance:</label>
            <input type="number" id="allowance" name="allowance" value="<?php echo htmlspecialchars($student['allowance']); ?>" required>

            <div class="actions">
                <button type="submit">Save Changes</button>
                <button type="button" onclick="window.location.href='admin_page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
