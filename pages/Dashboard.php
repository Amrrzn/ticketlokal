<?php
include '../pages/connect.php';
session_start();

// Check if the organizer is logged in
if (!isset($_SESSION['OrgEmail'])) {
    header('Location: ../pages/login.php');
    exit();
}

// Fetch organizer details
$orgEmail = $_SESSION['OrgEmail'];
$stmt = $conn->prepare("SELECT * FROM organization WHERE OrgEmail = ?");
$stmt->bind_param("s", $orgEmail);
$stmt->execute();
$org = $stmt->get_result()->fetch_assoc();

// Fetch total tickets sold and total revenue
$ticketsStmt = $conn->prepare("
    SELECT 
        SUM(tp.Quantity) AS total_tickets_sold, 
        SUM(tp.TotalAmount) AS total_revenue 
    FROM ticket_purchases tp
    INNER JOIN ticket t ON tp.TicketID = t.TicketID
    INNER JOIN events e ON t.EventID = e.EventID
    WHERE e.OrgEmail = ?
");
$ticketsStmt->bind_param("s", $orgEmail);
$ticketsStmt->execute();
$ticketsData = $ticketsStmt->get_result()->fetch_assoc();

// Fetch the number of events created
$eventsStmt = $conn->prepare("SELECT COUNT(*) AS total_events FROM events WHERE OrgEmail = ?");
$eventsStmt->bind_param("s", $orgEmail);
$eventsStmt->execute();
$eventsData = $eventsStmt->get_result()->fetch_assoc();

// Fetch ticket sales details for each event
$salesStmt = $conn->prepare("
    SELECT 
        e.EventName AS event_title, 
        t.TicketType AS ticket_type, 
        t.TicketPrice AS ticket_price, 
        SUM(tp.Quantity) AS tickets_sold, 
        t.TicketQty - SUM(tp.Quantity) AS tickets_left, 
        SUM(tp.TotalAmount) AS total_earned 
    FROM ticket_purchases tp
    INNER JOIN ticket t ON tp.TicketID = t.TicketID
    INNER JOIN events e ON t.EventID = e.EventID
    WHERE e.OrgEmail = ?
    GROUP BY t.TicketID
");
$salesStmt->bind_param("s", $orgEmail);
$salesStmt->execute();
$salesDetails = $salesStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
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
    <main>
        <h1>Welcome, <?= htmlspecialchars($org['OrgName']); ?></h1>
        <section class="dashboard-overview">
            <div class="card">
                <h2>Total Tickets Sold</h2>
                <p><?= $ticketsData['total_tickets_sold'] ?: 0; ?></p>
            </div>
            <div class="card">
                <h2>Total Revenue</h2>
                <p>RM <?= number_format($ticketsData['total_revenue'] ?: 0, 2); ?></p>
            </div>
            <div class="card">
                <h2>Events Created</h2>
                <p><?= $eventsData['total_events'] ?: 0; ?></p>
            </div>
        </section>
        <section class="sales-details">
            <h2>Ticket Sales Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Ticket Type</th>
                        <th>Price (RM)</th>
                        <th>Tickets Sold</th>
                        <th>Tickets Left</th>
                        <th>Total Earned (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $salesDetails->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['event_title']); ?></td>
                            <td><?= htmlspecialchars($row['ticket_type']); ?></td>
                            <td><?= number_format($row['ticket_price'], 2); ?></td>
                            <td><?= $row['tickets_sold'] ?: 0; ?></td>
                            <td><?= max($row['tickets_left'], 0); ?></td>
                            <td><?= number_format($row['total_earned'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
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

