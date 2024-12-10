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

// Handle search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $search_query = "WHERE u.name LIKE '%$search_term%' OR s.mobile LIKE '%$search_term%' OR s.course LIKE '%$search_term%'";
}

// Fetch student records with search filter if applied
$students_query = "
    SELECT u.name, u.email, s.course, s.block_year, s.allowance, s.mobile, s.user_id 
    FROM students s
    JOIN user u ON u.id = s.user_id 
    $search_query
";
$students_result = mysqli_query($conn, $students_query);

include 'nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Students</title>
    <link rel="stylesheet" href="css/admin_page.css">
</head>
<body>
    <div class="admin-container">
        <h2>Admin Panel - Manage Students</h2>

        <!-- Search Form -->
        <form method="post" class="search-form">
            <input type="text" name="search_term" class="search-bar" placeholder="Search by name, mobile, or course">
            <button type="submit" name="search">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Block/Year</th>
                    <th>Allowance</th>
                    <th>Mobile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($students_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['block_year']); ?></td>
                        <td><?php echo htmlspecialchars($row['allowance']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $row['user_id']; ?>">Edit</a>
                            <a href="delete_student.php?id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
