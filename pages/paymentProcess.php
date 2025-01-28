<?php
// process_payment.php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tickets'], $_POST['totalAmount'])) {
    $email = $_SESSION['Email'] ?? null;
    $tickets = $_POST['tickets'];
    $totalAmount = floatval($_POST['totalAmount']);
    $purchaseDate = date('Y-m-d H:i:s');

    if (!$email) {
        die("You must be logged in to complete the purchase.");
    }

    // Fetch FirstName and LastName from the user table
    $stmt = $conn->prepare("SELECT FirstName, LastName FROM user WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);
    $stmt->fetch();
    $stmt->close();

    // If user details are not found, set default values
    if (!$firstName || !$lastName) {
        $firstName = 'John';
        $lastName = 'Doe';
    }

    // Initialize ticketDetails array in session
    $_SESSION['ticketDetails'] = []; // Reset to avoid conflicts

    $conn->begin_transaction();
    try {
        foreach ($tickets as $ticketID => $details) {
            $ticketID = intval($ticketID);
            $quantity = intval($details['quantity']);
            $ticketType = htmlspecialchars($details['TicketType']);
            $eventName = htmlspecialchars($details['EventName']);
            $price = floatval($details['Price']);
            $total = $price * $quantity;

            // Insert each ticket purchase into the database
            $stmt = $conn->prepare("
                INSERT INTO ticket_purchases (TicketID, Email, EventName, TicketType, PurchaseDate, Quantity, TotalAmount) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("issssdi", $ticketID, $email, $eventName, $ticketType, $purchaseDate, $quantity, $total);
            $stmt->execute();

            // Add one entry for each ticket (based on quantity) into session
            for ($i = 0; $i < $quantity; $i++) {
                $_SESSION['ticketDetails'][] = [
                    'EventID' => $details['EventID'],
                    'TicketID' => $ticketID,
                    'FirstName' => $firstName,
                    'LastName' => $lastName,
                    'Email' => $email,
                    'TicketType' => $ticketType,
                    'EventName' => $eventName,
                ];
            }
        }

        $conn->commit();
        header("Location: ticketDetail.php"); // Redirect to try.php
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Error processing purchase: " . $e->getMessage());
    }
} else {
    die("No payment data provided.");
}
?>
