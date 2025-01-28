<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['Email'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['Email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];

    $query = "UPDATE users SET first_name = ?, last_name = ?, phone_number = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $first_name, $last_name, $phone_number, $email);

    if ($stmt->execute()) {
        header('Location: profile.php'); // Redirect back to the profile page
        exit();
    } else {
        echo "Error updating profile.";
    }
}
?>
