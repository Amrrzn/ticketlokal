<?php
// Database connection
include 'connect.php';
$isLoggedIn = isset($_SESSION['Email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - TicketLokal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="logo">TicketLokal</div>
        <div class="search-container">
            <input type="text" placeholder="Search..." class="search-bar">
        </div>
        <nav>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="home.php#about">About</a></li>
                    <li><a href="home.php#contact">Contact</a></li>
                    <?php if ($isLoggedIn): ?>
                        <button id="logoutButton">Sign Out</button>
                    <?php else: ?>
                        <a href="../pages/login.php"><button id="loginButton">Sign In</button></a>
                    <?php endif; ?>
                </ul>
            </nav>
              <div id="events-container">
                <!-- All events content will be displayed here -->
              </div>              
        </nav>
    </header>

    <div class="hero-text">
        <h1>Contact Us</h1>
        <p>Have any questions? Feel free to reach out to us:</p>
        <p>Email: support@ticketlokal.com</p>
        <p>Phone: +6012-3456789</p>
    </div>

    <script src="../js/script.js" defer></script>
</body>
</html>
