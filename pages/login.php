<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];


    // For non-admin users, proceed with regular login
    $query = "SELECT * FROM user WHERE Email = ? AND Password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Set session variables for the logged-in user
        $_SESSION['Email'] = $user['Email'];
        $_SESSION['FirstName'] = $user['FirstName'];
        $_SESSION['LastName'] = $user['LastName'];

        // Redirect to the home page
        header('Location: home.php');
        exit();
    } else {
        // Set an error message if login fails
        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: login.php'); // Redirect back to the login page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - TicketLokal</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
<header>
    <img src="../images/logo_white-removebg-preview-new.png" class="logo"></img>
    <div class="search-container">
        <input type="text" placeholder="Search Event Name" class="search-bar" oninput="fetchSuggestions(this.value)">
        <div class="suggestions" id="suggestions-box"></div>
    </div>
    <nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="events.php">Events</a></li>
        <?php if (isset($_SESSION['OrgEmail'])): ?>
            <li><a href="createEvent.php">Create Event</a></li>
            <li><a href="Dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        
        <li class="dropdown">
            <?php if (isset($_SESSION['Email']) || isset($_SESSION['OrgEmail'])): ?>
                <!-- Use Logout Image if Logged In -->
                <img src="../images/logout.png" onclick="toggleDropdown()" class="profile-icon" alt="Logout Icon">
            <?php else: ?>
                <!-- Use Login Image if Not Logged In -->
                <img src="../images/login.png" onclick="toggleDropdown()" class="profile-icon" alt="Login Icon">
            <?php endif; ?>
            <div class="dropdown-menu" id="profile-dropdown">
                <?php if (isset($_SESSION['Email']) || isset($_SESSION['OrgEmail'])): ?>
                    <a href="profile.php">Profile</a>
                    <a href="../pages/logout.php">Sign Out</a>
                <?php else: ?>
                    <a href="../pages/login.php">Sign In</a>
                <?php endif; ?>
            </div>
        </li>
    </ul>
    </nav>
</header>

    <!-- Sign In Form -->
    <div class="signin-container" id="signin-form" name="signin">
        <h1 class="signin-header">Sign In</h1>
        <form method="post" action="loginBackend.php">
            <div class="signin-input-container">
                <span class="signin-icon">✉️</span>
                <input class="signin-input" type="email" placeholder="Email Address" id="Email" name="Email" required>
            </div>
            <div class="signin-input-container">
                <span class="signin-icon">🔒</span>
                <input class="signin-input" type="password" placeholder="Password" id="Password" name="Password" required>
            </div>
            <button type="submit" name="signin" class="signin-btn">Sign In</button>
        </form>
        <p class="signin-text">Don't have an account?</p>
        <button class="signin-switch-btn" onclick="showSignUpAsCustomer()()">Sign Up As Customer</button>
        <button class="signin-switch-btn" onclick="showSignUpAsOrganizer()()">Sign Up As Organizer</button>
    </div>

    <!-- Customer Form -->
    <div class="signup-container hidden" id="customer-form" name="signup">
    <h1 class="signup-header">Sign Up</h1>
        <form method="POST" action="loginBackend.php">
            <div class="signup-input-container">
                <span class="signup-icon">👤</span>
                <input class="signup-input" type="text" placeholder="First Name" id="FirstName" name="FirstName" required>
            </div>
            <div class="signup-input-container">
                <span class="signup-icon">👤</span>
                <input class="signup-input" type="text" placeholder="Last Name" id="LastName" name="LastName" required>
            </div>
            <div class="signup-input-container">
                <span class="signup-icon">✉️</span>
                <input class="signup-input" type="email" placeholder="Email Address" id="Email" name="Email" required>
            </div>
            <div class="signup-input-container">
                <span class="signup-icon">🔒</span>
                <input class="signup-input" type="password" placeholder="Password" id="Password" name="Password" required>
            </div>
            <div class="signup-input-container">
                <span class="signup-icon">🔒</span>
                <input class="signup-input" type="password" placeholder="Confirm Password" name="ConfirmPassword" required>
            </div>
            <div class="signup-input-container">
                <span class="signup-icon">🆔</span>
                <input class="signup-input" type="text" placeholder="IC (e.g., 123456-78-1234)" id="IC" name="IC" pattern="\d{6}-\d{2}-\d{4}" title="Please enter a valid IC number in the format XXXXXX-XX-XXXX" required>
            </div>
            <div class="signup-input-container">
                <span class="signup-icon">📱</span>
                <input class="signup-input" type="tel" placeholder="PhoneNum" id="PhoneNum" name="PhoneNum" required>
            </div>
            <button type="submit" name="signup" class="signup-btn">Sign Up</button>
        </form>
        <p class="signup-text">Already have an account?</p>
        <button class="signup-switch-btn" onclick="showSignIn()">Sign In</button>
    </div>
    
    <!-- Organizer Form -->
    <div class="signup-container hidden" id="organizer-form" name="signup">
    <h1 class="signup-header">Sign Up</h1>
        <form method="POST" action="loginBackend.php" enctype="multipart/form-data">
            <div class="signup-input-container">
                <input class="signup-input" type="text" placeholder="Organization Name" id="OrgName" name="OrgName" required>
            </div>
            <div class="signup-input-container">
                <input class="signup-input" type="email" placeholder="Email Address" id="OrgEmail" name="OrgEmail" required>
            </div>
            <div class="signup-input-container">
                <input class="signup-input" type="password" placeholder="Password" id="OrgPassword" name="OrgPassword" required>
            </div>
            <div class="signup-input-container">
                <input class="signup-input" type="password" placeholder="Confirm Password" name="ConfirmPassword" required>
            </div>
            <div class="signup-input-container">
                <input class="signup-input" type="tel" placeholder="Contact Number" id="OrgContact" name="OrgContact" required>
            </div>
            <div class="signup-input-container">
                <input class="signup-input" type="tel" placeholder="Organization URL" id="OrgURL" name="OrgURL" required>
            </div>
            <div class="signup-input-container">
                <input class="signup-input" type="text" placeholder="SSM Registered Number" id="SSM" name="SSM" title="Please enter a valid SSM Registered Number" required>
            </div>
            <label for="SSMForm">SSM Registered Form:</label>
                <input type="file" id="SSMForm" name="SSMForm" accept="image/*"><br>
            </fieldset>
        <button type="submit" name="orgsignup" class="signup-btn">Sign Up</button>
        </form>
        <p class="signup-text">Already have an account?</p>
        <button class="signup-switch-btn" onclick="showSignIn()">Sign In</button>
    </div>
    <footer>
        <div class="main">
            <!-- Left-aligned stacked links -->
            <div class="footer-links">
                <a href="home.php#about">About Us</a>
                <a href="home.php#contact">Contact Us</a>
                <a href="home.php#feedback">Submit a Feedback</a>
            </div>

            <!-- Centered Logo -->
            <div class="footer-logo">
                <img src="../images/logo_white-removebg-preview-new.png" alt="TicketLokal Logo">
            </div>

            <!-- Right-aligned Social Media -->
            <div class="footer-right">
                <div class="footer-align">
                    <p>&copy; 2025 TicketLokal. All rights reserved.</p>
                    <div class="social-media">
                        <a href="https://www.facebook.com/" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/" target="_blank" aria-label="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://www.twitter.com/" target="_blank" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>    
    <script src="../js/script.js"></script>
</body>
</html>