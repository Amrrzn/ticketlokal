<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orgEmail = $_SESSION['OrgEmail'];
    $eventName = $_POST['EventName'];
    $category = $_POST['Category'];
    $description = $_POST['Description'];
    $date = $_POST['Date'];
    $time = $_POST['Time'];
    $venue = $_POST['Venue'];
    $venueAddress = $_POST['VenueAddress'];

    // Save event details
    $stmt = $conn->prepare("
        INSERT INTO events (OrgEmail, EventName, Category, Description, Date, Time, Venue, VenueAddress, EventImage, SeatMap) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $eventImage = $_FILES['EventImage']['name'];
    move_uploaded_file($_FILES['EventImage']['tmp_name'], "../images/" . $eventImage);
    
    $seatMap = null; // Default value
    if (!empty($_FILES['SeatMap']['name'])) {
        $seatMap = $_FILES['SeatMap']['name'];
        move_uploaded_file($_FILES['SeatMap']['tmp_name'], "../images/" . $seatMap);
    }
    
    $stmt->bind_param("ssssssssss", $orgEmail, $eventName, $category, $description, $date, $time, $venue, $venueAddress, $eventImage, $seatMap);
    $stmt->execute();
    $eventID = $stmt->insert_id;

    // Save ticket details with seat maps
    foreach ($_POST['TicketTypes'] as $index => $ticket) {
        $type = $ticket['type'];
        $price = floatval($ticket['price']);
        $quantity = intval($ticket['quantity']);

        // Insert ticket details
        $stmtTicket = $conn->prepare("
            INSERT INTO ticket (EventID, TicketType, TicketPrice, TicketQty)
            VALUES (?, ?, ?, ?)
        ");
        $stmtTicket->bind_param("isdi", $eventID, $type, $price, $quantity);
        $stmtTicket->execute();
        $ticketID = $stmtTicket->insert_id;
    }

    echo "Event created successfully!";
    header("Location: Dashboard.php");
}

?>
