<?php
include 'connect.php';
session_start();
$isLoggedIn = isset($_SESSION['Email']);

if (!isset($_SESSION['Email'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$email = $_SESSION['Email']; // Get logged-in user's email
$query = "SELECT FirstName, LastName, Email, PhoneNum FROM user WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - TicketLokal</title>
    <link rel="stylesheet" href="../css/profile.css">
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
    <div class="profile-container">
      <h1>MY LOKAL</h1>
      <div class="sidebar">
        <ul>
          <li><a href="#profile" data-tab="profile" class="active">Profile</a></li>
          <li><a href="#my-events" data-tab="my-events">My Events</a></li>
        </ul>
      </div>
      <div class="content">
        <!-- Profile Tab -->
        <div id="profile" class="tab-content active">
          <h2>Profile Information</h2>
          <form action="update_profile.php" method="POST">
            <!-- Personal Details -->
            <fieldset>
              <legend>Personal Details</legend>
              <label>First Name:</label>
              <input type="text" name="first_name" value="<?= htmlspecialchars($user['FirstName']) ?>"><br>
              <label>Last Name:</label>
              <input type="text" name="last_name" value="<?= htmlspecialchars($user['LastName']) ?>"><br>
            </fieldset>

            <!-- My Contact Section -->
            <fieldset>
              <legend>My Contact</legend>
              <label>Email Address:</label>
              <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" readonly><br>
              <label>Phone Number:</label>
              <input type="text" name="phone_number" value="<?= htmlspecialchars($user['PhoneNum']) ?>"><br>
            </fieldset>

            <button type="submit">Save</button>
          </form>
        </div>


        <!-- My Events Tab -->
        <div id="my-events" class="tab-content">
          <h2>My Events</h2>
          <?php
          // Query to fetch ticket purchases for the logged-in user
          $query = "SELECT PurchaseID, EventName, TicketType, PurchaseDate, Quantity, TotalAmount FROM ticket_purchases WHERE Email = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('s', $email);
          $stmt->execute();
          $result = $stmt->get_result();

          // Check if there are any records
          if ($result->num_rows > 0) {
              echo "<table border='1' class='events-table'>
                      <tr>
                          <th>Purchase ID</th>
                          <th>Event Name</th>
                          <th>Ticket Type</th>
                          <th>Purchase Date</th>
                          <th>Quantity</th>
                          <th>Total Amount</th>
                      </tr>";
              // Display each record in a row
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>" . htmlspecialchars($row['PurchaseID']) . "</td>
                          <td>" . htmlspecialchars($row['EventName']) . "</td>
                          <td>" . htmlspecialchars($row['TicketType']) . "</td>
                          <td>" . htmlspecialchars($row['PurchaseDate']) . "</td>
                          <td>" . htmlspecialchars($row['Quantity']) . "</td>
                          <td>" . htmlspecialchars($row['TotalAmount']) . "</td>
                        </tr>";
              }
              echo "</table>";
          } else {
              echo "<p>No ticket purchases found.</p>";
          }
          ?>
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
<script src="../js/profile.js"></script>
</body>
</html>
