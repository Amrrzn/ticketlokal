<?php
// Database connection
include 'connect.php';
session_start();
$isLoggedIn = isset($_SESSION['Email']);

// Check for valid EventID
if (isset($_GET['EventID']) && filter_var($_GET['EventID'], FILTER_VALIDATE_INT)) {
    $eventID = intval($_GET['EventID']);

    // Fetch event details
    $sqlEvent = "SELECT e.*, o.OrgName, o.OrgURL 
                 FROM events e
                 INNER JOIN organization o ON e.OrgEmail = o.OrgEmail
                 WHERE e.EventID = ?";
    $stmtEvent = $conn->prepare($sqlEvent);
    $stmtEvent->bind_param("i", $eventID);
    $stmtEvent->execute();
    $resultEvent = $stmtEvent->get_result();

    if ($resultEvent->num_rows > 0) {
        $event = $resultEvent->fetch_assoc();

        // Fetch ticket categories for this event
        $sqlTickets = "SELECT * FROM ticket WHERE EventID = ?";
        $stmtTickets = $conn->prepare($sqlTickets);
        $stmtTickets->bind_param("i", $eventID);
        $stmtTickets->execute();
        $resultTickets = $stmtTickets->get_result();

        // Fetch seat map for this event
        $sqlSeatMap = "SELECT SeatMap FROM events WHERE EventID = ?";
        $stmtSeatMap = $conn->prepare($sqlSeatMap);
        $stmtSeatMap->bind_param("i", $eventID);
        $stmtSeatMap->execute();
        $resultSeatMap = $stmtSeatMap->get_result();

        $seatMap = null; // Default if no seat map exists
        if ($resultSeatMap->num_rows > 0) {
            $seatMap = $resultSeatMap->fetch_assoc();
        }
    } else {
        die("Event not found.");
    }
} else {
    die("Invalid event ID.");
}

$hasFreeTickets = false;
while ($ticket = $resultTickets->fetch_assoc()) {
    if ($ticket['TicketPrice'] == 0) {
        $hasFreeTickets = true;
        break;
    }
}
$resultTickets->data_seek(0); // Reset pointer for reuse
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['EventName']); ?></title>
    <link rel="stylesheet" href="../css/eventDetail.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
<header>
    <img src="../images/logo_white-removebg-preview-new.png" class="logo">
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
                    <img src="../images/logout.png" onclick="toggleDropdown()" class="profile-icon" alt="Logout Icon">
                <?php else: ?>
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

<div class="event-header">
    <h1><?php echo htmlspecialchars($event['EventName']); ?></h1>
    <img src="../images/<?php echo htmlspecialchars($event['EventImage']); ?>" alt="Event Picture">
</div>

<div class="event-details-container">
    <div class="event-details-content">
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['Date']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['Description']); ?></p>
        <p><strong>Organizer:</strong> 
            <a href="<?php echo htmlspecialchars($event['OrgURL']); ?>" target="_blank">
                <?php echo htmlspecialchars($event['OrgName']); ?>
            </a>
        </p>
        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['Venue']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($event['VenueAddress']); ?></p>
    </div>

    <!-- Seat Map Section -->
    <div class="seat-map-container">
        <h2>Seat Map</h2>
        <?php if ($seatMap): ?>
            <img src="../images/<?php echo htmlspecialchars($seatMap['SeatMap']); ?>" alt="Seat Map" class="seat-map">
        <?php else: ?>
            <p>No seat map available for this event.</p>
        <?php endif; ?>
    </div>

    <!-- Ticket Selection Section -->
    <div class="ticket-selection">
        <p><b>Available Tickets</b></p>
        <?php if ($resultTickets->num_rows > 0): ?>
            <form action="cart.php" method="POST" class="ticket-form">
                <div class="ticket-container">
                    <?php while ($ticket = $resultTickets->fetch_assoc()): ?>
                        <div class="ticket-item">
                            <label>
                                <span><strong><?php echo htmlspecialchars($ticket['TicketType']); ?></strong></span>
                                <?php if ($ticket['TicketPrice'] == 0): ?>
                                    <span><strong>Price:</strong> Free</span>
                                <?php else: ?>
                                    <span><strong>Price: RM <?php echo number_format($ticket['TicketPrice'], 2); ?></strong></span>
                                    <input 
                                        type="number" 
                                        name="tickets[<?php echo $ticket['TicketID']; ?>][quantity]" 
                                        class="quantity-input" 
                                        data-price="<?php echo $ticket['TicketPrice']; ?>" 
                                        min="0" 
                                        value="0">
                                    <input type="hidden" name="tickets[<?php echo $ticket['TicketID']; ?>][EventID]" value="<?php echo htmlspecialchars($ticket['EventID']); ?>">
                                    <input type="hidden" name="tickets[<?php echo $ticket['TicketID']; ?>][TicketType]" value="<?php echo htmlspecialchars($ticket['TicketType']); ?>">
                                    <input type="hidden" name="tickets[<?php echo $ticket['TicketID']; ?>][Price]" value="<?php echo $ticket['TicketPrice']; ?>">
                                    <input type="hidden" name="tickets[<?php echo $ticket['TicketID']; ?>][EventName]" value="<?php echo htmlspecialchars($event['EventName']); ?>">
                                    <input type="hidden" name="tickets[<?php echo $ticket['TicketID']; ?>][Date]" value="<?php echo htmlspecialchars($event['Date']); ?>">
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="total-container">
                    <p><strong>Total Amount: RM <span id="totalAmount">0.00</span></strong></p>
                </div>
                <button type="submit" class="button">Add to Cart</button>
            </form>
        <?php else: ?>
            <p>No tickets available for this event.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('input', (event) => {
        const inputs = document.querySelectorAll('.quantity-input');
        let total = 0;

        inputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.getAttribute('data-price')) || 0;
            total += quantity * price;
        });

        document.getElementById('totalAmount').textContent = total.toFixed(2);
    });
</script>

<footer>
    <div class="main">
        <div class="footer-links">
            <a href="home.php#about">About Us</a>
            <a href="home.php#contact">Contact Us</a>
            <a href="home.php#feedback">Submit a Feedback</a>
        </div>
        <div class="footer-logo">
            <img src="../images/logo_white-removebg-preview-new.png" alt="TicketLokal Logo">
        </div>
        <div class="footer-right">
            <div class="footer-align">
                <p>&copy; 2025 TicketLokal. All rights reserved.</p>
                <div class="social-media">
                    <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/" target="_blank"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
