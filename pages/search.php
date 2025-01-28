<?php 
session_start();
include("../pages/connect.php");

$search = isset($_GET['query']) ? $_GET['query'] : '';

if (!empty($search)) {
    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT EventID, Title FROM events WHERE Title LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    
    // Log the search term for debugging
    error_log("Search term: " . $searchTerm);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch results
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = ['EventID' => $row['EventID'], 'Title' => $row['Title']];
        }

        // Log the JSON response for debugging
        error_log("JSON Response: " . json_encode($events));
        
        // Return results as JSON
        echo json_encode($events);
    } else {
        // Log SQL error
        error_log("SQL Error: " . $stmt->error);
    }
} 
?>
