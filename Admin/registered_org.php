<?php
// Include database connection and authentication check
include '../pages/connect.php';
session_start();

if (!isset($_SESSION['isAdmin'])) {
    header("Location: admin_login.php");
    exit();
}
// Fetch organizations based on status filter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'All';

if ($statusFilter === 'All') {
    $query = "SELECT * FROM organization ORDER BY CreatedAt DESC";
} else {
    $query = "SELECT * FROM organization WHERE Status = '$statusFilter' ORDER BY CreatedAt DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Organizations</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h1>Registered Organizations</h1>

        <!-- Filter by Status -->
        <form method="GET" class="filter-form">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="All" <?= $statusFilter === 'All' ? 'selected' : ''; ?>>All</option>
                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Approved" <?= $statusFilter === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="Rejected" <?= $statusFilter === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SSM ID Number</th>
                    <th>Organization Name</th>
                    <th>Organization Email</th>
                    <th>Organization Contact</th>
                    <th>Organization URL</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ID']); ?></td>
                        <td><?= htmlspecialchars($row['SSMNumber']); ?></td>
                        <td><?= htmlspecialchars($row['OrgName']); ?></td>
                        <td><?= htmlspecialchars($row['OrgEmail']); ?></td>
                        <td><?= htmlspecialchars($row['OrgContact']); ?></td>
                        <td><a href="<?= htmlspecialchars($row['OrgURL']); ?>" target="_blank">Visit</a></td>
                        <td><?= htmlspecialchars($row['CreatedAt']); ?></td>
                        <td><?= htmlspecialchars($row['Status']); ?></td>
                        <td>
                            <?php if ($row['Status'] === 'Pending') { ?>
                                <form method="POST" action="orgApproval.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['ID']; ?>">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                    <button type="submit" name="action" value="reject">Reject</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
