<?php
include '../pages/connect.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['isAdmin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch events based on status
$statusFilter = isset($_GET['EventStatus']) ? $_GET['EventStatus'] : 'Pending';

$query = $conn->prepare("SELECT * FROM events WHERE EventStatus = ?");
$query->bind_param("s", $statusFilter);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Event Approvals</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h1>Event Approvals</h1>

        <!-- Filter Events -->
        <form method="GET">
            <label for="EventStatus">Filter by status:</label>
            <select name="EventStatus" id="EventStatus" onchange="this.form.submit()">
                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Approved" <?= $statusFilter === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="Rejected" <?= $statusFilter === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Organizer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['EventName']); ?></td>
                        <td><?= htmlspecialchars($row['Category']); ?></td>
                        <td><?= htmlspecialchars($row['Date']); ?></td>
                        <td><?= htmlspecialchars($row['OrgName']); ?></td>
                        <td>
                            <?php if ($row['EventStatus'] === 'Pending') { ?>
                                <form method="POST" action="process_approval.php" style="display:inline;">
                                    <input type="hidden" name="event_id" value="<?= $row['EventID']; ?>">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                    <button type="submit" name="action" value="reject">Reject</button>
                                </form>
                            <?php } ?>
                            <a href="view_event.php?id=<?= $row['EventID']; ?>">View</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
