<?php
session_start();

// Check if ticketDetails exists and is an array
if (!isset($_SESSION['ticketDetails']) || !is_array($_SESSION['ticketDetails'])) {
    die("No ticket details found. Please complete the payment process first.");
}

// Retrieve ticket details from the session
$ticketDetails = $_SESSION['ticketDetails'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <link rel="stylesheet" href="../css/ticketDetail.css">
</head>
<body>
    <?php foreach ($ticketDetails as $ticket): ?>
    <div class="ticket-container">
        <div class="ticket-header">TicketLocal</div>
        <img src="../images/qr code.png" alt="Event Image">
        <div class="ticket-info">
            <strong><?php echo htmlspecialchars($ticket['FirstName'] . ' ' . $ticket['LastName']); ?></strong>
            <div class="TicketType">
            <strong><?php echo htmlspecialchars($ticket['TicketType']); ?></strong>
            </div>
        </div>
        <div class="info-container">
            <div class="full-name">
            <strong><?php echo htmlspecialchars($ticket['EventName']); ?></strong>
            </div>
            <div class="email"><?php echo htmlspecialchars($ticket['Email']); ?></div>
        </div>
        
        <div class="terms">
            <p>Terms & Conditions</p>
            <ul>
                <p>Keep this ticket safe. It is required for entry to the event.</p>
                <p>This ticket is non-transferable and non-refundable.</p>
                <p>Ensure your details are correct before the event.</p>
                <p>The event organizer reserves the right to make changes to the event.</p>
                <p>Contact support at <a href="mailto:support@ticketlokal.com">support@ticketlokal.com</a> for assistance.</p>
            </ul>
        </div>      

        <!-- Button to Download Ticket as PDF -->
        <form action="downloadTicket.php" method="POST">
            <input type="hidden" name="EventName" value="<?php echo htmlspecialchars($ticket['EventName']); ?>">
            <input type="hidden" name="FullName" value="<?php echo htmlspecialchars($ticket['FirstName'] . ' ' . $ticket['LastName']); ?>">
            <input type="hidden" name="Email" value="<?php echo htmlspecialchars($ticket['Email']); ?>">
            <input type="hidden" name="TicketType" value="<?php echo htmlspecialchars($ticket['TicketType']); ?>">
            <button type="submit">Download Ticket as PDF</button>
            <button type="button" onclick="location.href='../pages/home.php';">Back To Home</button>
        </form>
    </div>
    <hr> <!-- Divider between tickets -->
    <?php endforeach; ?>
</body>
</html>
