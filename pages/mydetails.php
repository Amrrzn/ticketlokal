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
    <title>Payment Summary - TicketLokal</title>
    <link rel="stylesheet" href="../css/style.css">
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
    <div class="profile-form">
      <form>
        <label>Email Address</label>
        <input type="email" value="amirrizuan1999@gmail.com" readonly>
        <a href="#">Change Email</a> | <a href="#">Change Password</a>

        <label>First Name</label>
        <input type="text" value="Amir">

        <label>Last Name</label>
        <input type="text" value="Rizuan">

        <label>Gender</label>
        <div class="gender-options">
          <input type="radio" name="gender" value="female"> Female
          <input type="radio" name="gender" value="male"> Male
          <input type="radio" name="gender" value="not-provided" checked> Not provided
        </div>

        <label>Birthday</label>
        <input type="date">

        <label>Country</label>
        <select>
          <option value="Malaysia" selected>Malaysia</option>
          <option value="United Kingdom">United Kingdom</option>
        </select>

        <label>Language</label>
        <select>
          <option value="en" selected>English (United Kingdom)</option>
          <option value="ms">Malay</option>
        </select>

        <label>Postcode</label>
        <input type="text" value="68100">
      </form>
    </div>
</body>
</html>
