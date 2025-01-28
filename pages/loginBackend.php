<?php
include 'connect.php';
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Helper function to redirect with a message
function redirect_with_message($venue, $message) {
    $_SESSION['error'] = $message;
    header("Location: $venue");
    exit();
}

// Handle Sign-In
if (isset($_POST['signin'])) {
    $email = trim($_POST['Email']);
    $password = trim($_POST['Password']);

    if (empty($email) || empty($password)) {
        redirect_with_message("../pages/login.php", "Please fill in all fields.");
    }

    // Check in `user` table (for customers)
    $stmt = $conn->prepare("SELECT * FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['UserType'] = 'customer';
            header("Location: ../pages/home.php");
            exit();
        } else {
            redirect_with_message("../pages/login.php", "Incorrect password. Please try again.");
        }
    }

    // Check in `organization` table (for organizers)
    $stmt = $conn->prepare("SELECT * FROM organization WHERE OrgEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $org = $result->fetch_assoc();
        if (password_verify($password, $org['OrgPassword'])) {
            if ($org['Status'] === 'Approved') {
                $_SESSION['OrgEmail'] = $org['OrgEmail'];
                $_SESSION['UserType'] = 'organization';
                header("Location: ../pages/home.php");
                exit();
            } elseif ($org['Status'] === 'Pending') {
                redirect_with_message("../pages/login.php", "Your account is still pending approval.");
            } else {
                redirect_with_message("../pages/login.php", "Your account has been rejected. Please contact support.");
            }
        } else {
            redirect_with_message("../pages/login.php", "Incorrect password. Please try again.");
        }
    }

    // If email not found in either table
    redirect_with_message("../pages/login.php", "Email not registered. Please sign up first.");
}

// Handle User Sign-Up
if (isset($_POST['signup'])) {
    $firstName = trim($_POST['FirstName']);
    $lastName = trim($_POST['LastName']);
    $email = trim($_POST['Email']);
    $password = trim($_POST['Password']);
    $confirmPassword = trim($_POST['ConfirmPassword']);
    $IC = trim($_POST['IC']);
    $phoneNum = trim($_POST['PhoneNum']);

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($IC) || empty($phoneNum)) {
        redirect_with_message("../pages/login.php", "All fields are required.");
    }

    if ($password !== $confirmPassword) {
        redirect_with_message("../pages/login.php", "Passwords do not match.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = $conn->prepare("SELECT * FROM user WHERE Email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        redirect_with_message("../pages/login.php", "Email already exists! Please log in.");
    } else {
        $stmt = $conn->prepare("INSERT INTO user (FirstName, LastName, Email, Password, IC, PhoneNum) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $hashedPassword, $IC, $phoneNum);
        if (!$stmt->execute()) {
            die("Error: " . $stmt->error);
        }
        redirect_with_message("../pages/login.php", "Registration successful! You can now sign in.");
    }
}

// Handle Organization Sign-Up
if (isset($_POST['orgsignup'])) {
    $orgName = trim($_POST['OrgName']);
    $orgEmail = trim($_POST['OrgEmail']);
    $orgPassword = trim($_POST['OrgPassword']);
    $confirmPassword = trim($_POST['ConfirmPassword']);
    $orgContact = trim($_POST['OrgContact']);
    $orgURL = trim($_POST['OrgURL']);
    $ssmNumber = trim($_POST['SSM']);
    $ssmForm = $_FILES['SSMForm'];

    if (empty($orgName) || empty($orgEmail) || empty($orgPassword) || empty($confirmPassword) || empty($orgContact) || empty($orgURL) || empty($ssmNumber) || empty($ssmForm)) {
        redirect_with_message("../pages/login.php", "All fields are required.");
    }

    if ($orgPassword !== $confirmPassword) {
        redirect_with_message("../pages/login.php", "Passwords do not match.");
    }

    $hashedPassword = password_hash($orgPassword, PASSWORD_DEFAULT);

    // Handle file upload
    $targetDir = "../images/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($ssmForm['name']);
    if (!move_uploaded_file($ssmForm['tmp_name'], $targetFile)) {
        die("Failed to upload SSM form. Please check folder permissions.");
    }

    // Insert organization data into the database with `Pending` status
    $stmt = $conn->prepare("INSERT INTO organization (OrgName, OrgEmail, OrgPassword, OrgContact, OrgURL, SSMNumber, SSMForm, Status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
    if ($stmt) {
        $stmt->bind_param("sssssss", $orgName, $orgEmail, $hashedPassword, $orgContact, $orgURL, $ssmNumber, $targetFile);
        if ($stmt->execute()) {
            redirect_with_message("../pages/login.php", "Organization registration successful! Pending admin approval.");
        } else {
            die("Error during registration: " . $stmt->error);
        }
    } else {
        die("Database error: Failed to prepare query.");
    }
}

?>
