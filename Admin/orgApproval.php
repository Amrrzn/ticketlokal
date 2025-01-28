<?php
// Include database connection
include '../pages/connect.php';
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['isAdmin'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the organizer ID and action (approve/reject) from the POST request
    $id = $_POST['id']; // 'id' is sent from the form in registered_org.php
    $action = $_POST['action']; // 'approve' or 'reject'

    // Set the status based on the action
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    // Update the organization's status in the database
    $stmt = $conn->prepare("UPDATE organization SET Status = ? WHERE ID = ?");
    $stmt->bind_param("si", $status, $id);

    // Execute the query and handle success or error
    if ($stmt->execute()) {
        // Redirect back to the registered_org.php with a success message
        header("Location: registered_org.php?message=Organizer $status successfully.");
        exit();
    } else {
        // Show an error message on failure
        echo "Error: " . $stmt->error;
    }
} else {
    // Redirect to the organization page if accessed without POST
    header("Location: registered_org.php");
    exit();
}
?>
