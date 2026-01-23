<?php
session_start();
require '../config/db.php';

// Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = connection();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['status'])) {
    $update = $conn->prepare("UPDATE tickets SET status=? WHERE id=?");
    $update->execute([$_POST['status'], $_POST['ticket_id']]);
    header("Location: admin_dashboard.php"); // refresh page to show updated status
    exit();
}

// Fetch all tickets and their owners
$stmt = $conn->prepare("SELECT t.id, t.subject, t.issue_type, t.priority, t.status, t.created_at, c.name AS customer_name 
                        FROM tickets t 
                        JOIN customer_acc c ON t.user_id = c.id
                        ORDER BY t.created_at DESC");
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        select {
            padding: 4px;
        }

        button {
            padding: 4px 8px;
        }

        .logout-btn {
            padding: 6px 12px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </header>

    <h2>All Tickets</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Issue Type</th>
            <th>Priority</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Update Status</th>
        </tr>
        <?php foreach ($tickets as $ticket) : ?>
            <tr>
                <td><?php echo $ticket['id']; ?></td>
                <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                <td><?php echo htmlspecialchars($ticket['issue_type']); ?></td>
                <td><?php echo htmlspecialchars($ticket['priority']); ?></td>
                <td><?php echo htmlspecialchars($ticket['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                <td><?php echo $ticket['created_at']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                        <select name="status">
                            <option value="Open" <?php if ($ticket['status'] == 'Open') echo 'selected'; ?>>Open</option>
                            <option value="In Progress" <?php if ($ticket['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Closed" <?php if ($ticket['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>