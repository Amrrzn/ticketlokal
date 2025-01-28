<?php

include 'connect.php';
session_start();
$isLoggedIn = isset($_SESSION['Email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - TicketLokal</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
                    <a reef="../pages/cart.php">Cart</a>
                    <a href="../pages/logout.php">Sign Out</a>
                <?php else: ?>
                    <a href="../pages/login.php">Sign In</a>
                <?php endif; ?>
            </div>
        </li>
    </ul>
    </nav>
</header>
    <section id="home" class="home-container">
        <div class="home-content">
            <div class="slideshow-container">
                <!-- Slides -->
                <div class="mySlides">
                    <div class="background" style="background-image: url('../images/Hausboom Background.jpg');"></div>
                    <img src="../images/Hausboom Background.jpg" alt="Slide 1">
                </div>
                <div class="mySlides">
                    <div class="background" style="background-image: url('../images/Rage Background.jpeg');"></div>
                    <img src="../images/Rage Background.jpeg" alt="Slide 2">
                </div>
                <div class="mySlides">
                    <div class="background" style="background-image: url('../images/Sixthsence Background.jpeg');"></div>
                    <img src="../images/Sixthsence Background.jpeg" alt="Slide 3">
                </div>

                <!-- Navigation buttons -->
                <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
                <button class="next" onclick="changeSlide(1)">&#10095;</button>

                <!-- Dots for slide indicators -->
                <div class="dot-container">
                    <span class="dot" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                    <span class="dot" onclick="currentSlide(3)"></span>
                </div>
            </div>

            <div class="home-text">
                <h1>Discover Exciting Events in Malaysia with TicketLokal</h1>
                <p>Find and book tickets for concerts, festivals, and more!</p>
                <a href="events.php" class="event-btn">Explore Events</a>
            </div>
        </div>
    </section>

    <section id="about" class="about-container">
        <div class="about-content">
            <div class="about-image">
                <img src="../images/office.jpg" alt="Office">

            </div>
            <div class="about-text">
                <h1>About TicketLokal</h1>
                <p>TicketLokal is your one-stop platform for discovering and booking tickets for concerts, festivals, and events in Malaysia. We provide easy access to the latest happenings and ensure a seamless ticket purchasing experience.</p>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-container">
        <div class="contact-content">
            <div class="contact-image">
                <img src="../images/customer service.jpg" alt="Customer Service">
            </div>
            <div class="contact-text">
                <h1>Contact Us</h1>
                <p>Have any questions? Feel free to reach out to us:</p>
                <p>Email: support@ticketlokal.com</p>
                <p>Phone: +6012-3456789</p>
            </div>
        </div>
    </section>
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

