<?php
session_start();
session_destroy();  // Destroy the session
header('location: ../login.php');  // Redirect to login page
exit();
?>