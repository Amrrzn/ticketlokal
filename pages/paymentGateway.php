<?php
// paymentGateway.php
session_start();
include 'connect.php';


// Ensure session has cart and payment data
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("No cart data found. <a href='cart.php'>Go back to cart</a>");
}

if (isset($_POST['tickets'])) {
    foreach ($_POST['tickets'] as $ticketID => $details) {
        $ticketID = intval($ticketID); // Ensure TicketID is an integer
        $quantity = intval($details['quantity']);
    }
} else {
    die("No ticket data found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['totalAmount'])) {
    // Validate and sanitize POST data
    $totalAmount = floatval($_POST['totalAmount']);
    $_SESSION['payment'] = [
        'totalAmount' => $totalAmount,
        'tickets' => $_SESSION['cart'], // Include cart details
    ] ;
}
$stmt = $conn->prepare("SELECT COUNT(*) FROM ticket WHERE TicketID = ?");
$stmt->bind_param("i", $ticketID);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count == 0) {
    throw new Exception("Invalid TicketID: $ticketID. This TicketID does not exist in the ticket table.");
}


// Ensure 'payment' session data exists
if (!isset($_SESSION['payment']) || !isset($_SESSION['payment']['totalAmount'])) {
    die("Payment data missing. <a href='cart.php'>Go back to cart</a>");
}

// Variables for display
$totalAmount = $_SESSION['payment']['totalAmount'];
$tickets = $_SESSION['payment']['tickets'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <link rel="stylesheet" href="../css/paymentgateway.css">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
<header>
    <img src="../images/logo_white-removebg-preview-new.png" class="logo"></img>
    <!-- Additional header content -->
</header>
<div class="payment-container">
    <h1>Payment Gateway</h1>
    <form action="paymentProcess.php" method="POST">
        <h2>Confirm Your Payment</h2>
        <p><strong>Total Amount: RM<?php echo number_format($totalAmount, 2); ?></strong></p>
        <input type="hidden" name="totalAmount" value="<?php echo $totalAmount; ?>">
        <?php foreach ($tickets as $ticketID => $details): ?>
            <input type="hidden" name="tickets[<?php echo $ticketID; ?>][TicketID]" value="<?php echo $ticketID; ?>">
            <input type="hidden" name="tickets[<?php echo $ticketID; ?>][EventID]" value="<?php echo htmlspecialchars($details['EventID']); ?>">
            <input type="hidden" name="tickets[<?php echo $ticketID; ?>][EventName]" value="<?php echo htmlspecialchars($details['EventName']); ?>">
            <input type="hidden" name="tickets[<?php echo $ticketID; ?>][quantity]" value="<?php echo htmlspecialchars($details['quantity']); ?>">
            <input type="hidden" name="tickets[<?php echo $ticketID; ?>][TicketType]" value="<?php echo htmlspecialchars($details['TicketType']); ?>">
            <input type="hidden" name="tickets[<?php echo $ticketID; ?>][Price]" value="<?php echo htmlspecialchars($details['Price']); ?>">
        <?php endforeach; ?>
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" class="button">Pay Now</button>
    </form>
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
</body>
</html>
