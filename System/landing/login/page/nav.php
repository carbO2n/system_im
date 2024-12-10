<?php
// Start session only if it hasn't already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verify if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header('location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <nav class="nav-bar">
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
