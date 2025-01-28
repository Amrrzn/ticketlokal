<?php
include("../pages/connect.php");
session_start();
$isLoggedIn = isset($_SESSION['Email']);

// Fetch approved events for display with pagination
$limit = 10; // Number of events per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Check for category filter
$category = isset($_GET['category']) ? $_GET['category'] : 'All';

// Query to fetch events and their lowest ticket price
$sql = "
    SELECT 
        e.*, 
        MIN(t.TicketPrice) AS LowestPrice 
    FROM events e
    LEFT JOIN ticket t ON e.EventID = t.EventID
    WHERE e.EventStatus = 'Approved'" . 
    ($category !== 'All' ? " AND e.Category = ?" : "") . 
    " GROUP BY e.EventID 
    LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if ($category !== 'All') {
    $stmt->bind_param("sii", $category, $offset, $limit);
} else {
    $stmt->bind_param("ii", $offset, $limit);
}

$stmt->execute();
$all_event = $stmt->get_result();

// Pagination logic
$total_sql = "SELECT COUNT(*) FROM events WHERE EventStatus = 'Approved'" . ($category !== 'All' ? " AND Category = ?" : "");
$total_stmt = $conn->prepare($total_sql);

if ($category !== 'All') {
    $total_stmt->bind_param("s", $category);
}

$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_events = $total_result->fetch_row()[0];
$total_pages = ceil($total_events / $limit);

// Fetch suggestions if a query is sent
$suggestions = [];
if (isset($_GET['query'])) {
    $search = $_GET['query'];
    $stmt = $conn->prepare("SELECT Title FROM events WHERE EventStatus = 'Approved' AND Title LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['Title'];
    }
    echo json_encode($suggestions);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - TicketLokal</title>
    <link rel="stylesheet" href="../css/events.css">
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
<section class="events">        
    <div class="event-text">
        <h1>All Events</h1>
        <p>Explore the most exciting events happening around Malaysia and grab your tickets now!</p>
    </div>
    <div class="event-category">
        <div class="filter-bar">
            <a href="events.php" class="filter-btn <?php echo ($category === 'All') ? 'active' : ''; ?>">All</a>
            <a href="events.php?category=Music" class="filter-btn <?php echo ($category === 'Music') ? 'active' : ''; ?>">Music</a>
            <a href="events.php?category=Comedy" class="filter-btn <?php echo ($category === 'Comedy') ? 'active' : ''; ?>">Comedy</a>
            <a href="events.php?category=Sport" class="filter-btn <?php echo ($category === 'Sport') ? 'active' : ''; ?>">Sport</a>
            <a href="events.php?category=Other" class="filter-btn <?php echo ($category === 'Other') ? 'active' : ''; ?>">Other</a>
        </div>
    </div>

    <div class="event-list">
        <?php while ($row = $all_event->fetch_assoc()) { ?>
            <div class="event-container">
                <div class="picture">
                    <img src="../images/<?php echo htmlspecialchars($row['EventImage']); ?>" alt="Event Picture">
                </div>
                <div class="event-info">
                    <div class="EventName"><?php echo htmlspecialchars($row["EventName"]); ?></div>
                    <div class="Location"><b>VENUE:  </b><?php echo htmlspecialchars($row["Venue"]); ?></div>
                    <div class="Date"><b>DATE: </b><?php echo htmlspecialchars($row["Date"]); ?></div>
                    <div class="Price">
                        <b>PRICE: </b>
                        <?php echo $row["LowestPrice"] == 0 ? "Free" : "RM" . number_format($row["LowestPrice"], 2); ?>
                </div>
                    <div class="Category"><b>CATEGORY: </b><?php echo htmlspecialchars($row["Category"]); ?></div>
                    <a class="Detail" href="../pages/eventDetail.php?EventID=<?php echo $row['EventID']; ?>">
                        <button>View Detail</button>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="events.php?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>" 
            class="pagination-link <?php echo ($page === $i) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</section>
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
<script src="../js/script.js"></script>
</body>
</html>
