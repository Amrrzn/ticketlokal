<?php
include 'connect.php';

$event_id = isset($_GET['EventID']) ? (int)$_GET['EventID'] : null;

if (!$event_id) {
    echo "No event selected.";
    exit();
}

$sql = "SELECT Price FROM events WHERE EventID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
    $price = $event['TicketPrice'];  // Retrieve event price from database
} else {
    echo "Event not found.";
    exit();
}
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
        <div class="logo">TicketLokal</div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <section class="buy-ticket-container">
        <div class="ticket-summary">
            <form action="../pages/process_payment.php" method="post" name="submit">
                <h1>Payment Summary</h1>
                <p><strong>Name:</strong> <?= htmlspecialchars($_GET['name'] ?? 'N/A'); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_GET['email'] ?? 'N/A'); ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($_GET['quantity'] ?? '0'); ?></p>
                <p><strong>Total Amount:</strong> RM<?= htmlspecialchars($price * ($_GET['total_amount'] ?? 0)); ?></p>
                <button type="submit" class="purchase-button">Proceed to Payment</button>
            </form>
        </div>
    </section>
    <script src="../js/script.js" defer></script>
</body>
</html>
