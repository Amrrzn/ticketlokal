<?php
// cart.php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tickets'])) {
    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Merge new tickets into the existing cart
    foreach ($_POST['tickets'] as $ticketID => $details) {
        // Validate and sanitize input data
        $ticketID = (int) $ticketID;
        $details['EventName'] = $details['EventName'] ?? 'Unknown Event';
        $details['Date'] = $details['Date'] ?? 'Unknown Date';
        $details['TicketType'] = $details['TicketType'] ?? 'Unknown Ticket Type';
        $details['quantity'] = isset($details['quantity']) ? max(0, intval($details['quantity'])) : 0;
        $details['Price'] = isset($details['Price']) ? max(0.00, floatval($details['Price'])) : 0.00;

        // Debugging: Ensure EventID is captured
        if (isset($details['EventID'])) {
            $_SESSION['EventID'] = $details['EventID']; // Persist EventID in the session
        }

        // Skip if quantity is zero or less
        if ($details['quantity'] <= 0) {
            continue;
        }

        if (isset($_SESSION['cart'][$ticketID])) {
            // If the ticket already exists in the cart, update the quantity
            $_SESSION['cart'][$ticketID]['quantity'] += $details['quantity'];
        } else {
            // Otherwise, add the new ticket
            $_SESSION['cart'][$ticketID] = $details;
        }
    }
}

// If the cart is empty, display a message
if (empty($_SESSION['cart'])) {
    die("Your cart is empty. <a href='../pages/events.php'>Back to Events</a>");
}

// Handle removing tickets from the cart
if (isset($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]); // Remove the specific ticket from the cart
    header("Location: cart.php"); // Refresh the page after removal
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/cart.css">
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
<div class="cart-container">
    <h1>Your Cart</h1>
    <form action="paymentGateway.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Event ID</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Ticket Type</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalAmount = 0;
                foreach ($_SESSION['cart'] as $ticketID => $details):
                    $quantity = intval($details['quantity']);
                    $price = floatval($details['Price']);
                    $subtotal = $quantity * $price;
                    $totalAmount += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticketID); ?></td>
                    <td><?php echo htmlspecialchars($details['EventID']); ?></td>
                    <td><?php echo htmlspecialchars($details['EventName']); ?></td>
                    <td><?php echo htmlspecialchars($details['Date']); ?></td>
                    <td><?php echo htmlspecialchars($details['TicketType']); ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td>RM<?php echo number_format($price, 2); ?></td>
                    <td>RM<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <!-- Remove Button -->
                        <a href="cart.php?remove=<?php echo $ticketID; ?>" class="remove-button">Remove</a>
                    </td>
                </tr>
                <input type="hidden" name="tickets[<?php echo $ticketID; ?>][TicketID]" value="<?php echo $ticketID; ?>">
                <input type="hidden" name="tickets[<?php echo $ticketID; ?>][EventName]" value="<?php echo htmlspecialchars($details['EventName']); ?>">
                <input type="hidden" name="tickets[<?php echo $ticketID; ?>][EventID]" value="<?php echo htmlspecialchars($details['EventID']); ?>">
                <input type="hidden" name="tickets[<?php echo $ticketID; ?>][quantity]" value="<?php echo $quantity; ?>">
                <input type="hidden" name="tickets[<?php echo $ticketID; ?>][TicketType]" value="<?php echo htmlspecialchars($details['TicketType']); ?>">
                <input type="hidden" name="tickets[<?php echo $ticketID; ?>][Price]" value="<?php echo $price; ?>">
                <?php endforeach; ?>
                <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
            </tbody>
        </table>
        <p><strong>Total Amount: RM<?php echo number_format($totalAmount, 2); ?></strong></p>
        <button type="submit" class="button">Proceed to Payment</button>
    </form>
    <button onclick="window.location.href='../pages/events.php';" class="backtoevents">Back to Events</button>
</div>
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
                    <a href="https://www.facebook.com/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.twitter.com/" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
