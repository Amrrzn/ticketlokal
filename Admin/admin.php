<?php
// Include database connection and authentication check
include '../pages/connect.php';
session_start();

// Check if the admin is logged in (optional)
if (!isset($_SESSION['isAdmin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Navigation Options -->
        <div class="admin-options">
            <a href="event_registered.php" class="admin-btn">View Registered Events</a>
            <a href="registered_org.php" class="admin-btn">View Registered Organizations</a>
        </div>
    </div>
</body>
</html>
