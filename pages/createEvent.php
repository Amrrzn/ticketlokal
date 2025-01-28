<?php
include 'connect.php';
session_start();

// Ensure the organizer is logged in
if (!isset($_SESSION['OrgEmail'])) {
    header('Location: login.php');
    exit();
}

// Fetch organizer details
$email = $_SESSION['OrgEmail'];
$stmt = $conn->prepare("SELECT OrgName, OrgEmail, OrgURL FROM organization WHERE OrgEmail = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $org = $result->fetch_assoc();
} else {
    die("Organizer details not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - TicketLokal</title>
    <link rel="stylesheet" href="../css/createEvent.css">
    <link rel="stylesheet" href="../css/home.css">
    <script>
        let ticketCount = 0;

        function addTicketField() {
            ticketCount++;
            const ticketDiv = document.createElement("div");
            ticketDiv.id = `TicketType${ticketCount}`;
            ticketDiv.classList.add("ticket-type");
            ticketDiv.innerHTML = `
                <h3>Ticket Type ${ticketCount}</h3>
                <label>Ticket Name:</label>
                <input type="text" name="TicketTypes[${ticketCount}][type]" placeholder="e.g., VIP" required><br>

                <label>Price (RM):</label>
                <input type="number" name="TicketTypes[${ticketCount}][price]" step="0.01" min="0" placeholder="0.00" required><br>

                <label>Number of Seats:</label>
                <input type="number" name="TicketTypes[${ticketCount}][quantity]" min="1" placeholder="e.g., 50" required><br>

            `;
            document.getElementById("ticket-details-container").appendChild(ticketDiv);
        }

        function removeTicketField() {
            if (ticketCount > 0) {
                const ticketDiv = document.getElementById(`TicketType${ticketCount}`);
                if (ticketDiv) {
                    ticketDiv.remove();
                    ticketCount--;
                }
            }
        }
    </script>
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
    <form action="createEventBackend.php" method="POST" enctype="multipart/form-data">
        <label>Event Name:</label>
        <input type="text" name="EventName" required>

        <label>Category:</label>
        <select name="Category" required>
            <option value="music">Music</option>
            <option value="comedy">Comedy</option>
            <option value="sport">Sport</option>
            <option value="other">Other</option>
        </select>

        <label>Description:</label>
        <textarea name="Description" rows="5" required></textarea>

        <label>Date:</label>
        <input type="date" name="Date" required>

        <label>Time:</label>
        <input type="time" name="Time" required>

        <label>Venue:</label>
        <input type="text" name="Venue" required>

        <label>Venue Address:</label>
        <input type="text" name="VenueAddress" required>

        <label>Event Image:</label>
        <input type="file" name="EventImage" accept="image/*" required>

        <label>Seat Map (Optional):</label>
        <input type="file" name="SeatMap" accept="image/*">

        <h2>Ticket Types</h2>
        <button type="button" onclick="addTicketField()">Add Ticket</button>
        <button type="button" onclick="removeTicketField()">Remove Last Ticket</button>
        <div id="ticket-details-container"></div>

        <div class="button-container">
            <button type="submit">Create Event</button>
        </div>
    </form>
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
</body>
</html>

