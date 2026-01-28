<?php
session_start();
require '../config/db.php';

// Only allow admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = connection();

// Get search term
$search = $_GET['search'] ?? '';

// Fetch tickets (with optional search)
if ($search !== '') {
    $stmt = $conn->prepare("
        SELECT t.id, t.subject, t.issue_type, t.priority, t.status, t.created_at,
               c.name AS customer_name
        FROM tickets t
        JOIN customer_acc c ON t.user_id = c.id
        WHERE t.subject LIKE ?
        ORDER BY t.created_at DESC
    ");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $conn->prepare("
        SELECT t.id, t.subject, t.issue_type, t.priority, t.status, t.created_at,
               c.name AS customer_name
        FROM tickets t
        JOIN customer_acc c ON t.user_id = c.id
        ORDER BY t.created_at DESC
    ");
    $stmt->execute();
}

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

        .msg {
            margin-left: 8px;
            font-size: 14px;
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

        .search-box {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome, Admin <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </header>

    <h2>All Tickets</h2>

    <!-- 🔍 SEARCH -->
    <form method="get" class="search-box">
        <input
            type="text"
            name="search"
            placeholder="Search by subject..."
            value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <?php if ($search): ?>
            <a href="admin_dashboard.php">Clear</a>
        <?php endif; ?>
    </form>

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

        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= $ticket['id'] ?></td>
                <td><?= htmlspecialchars($ticket['subject']) ?></td>
                <td><?= htmlspecialchars($ticket['issue_type']) ?></td>
                <td><?= htmlspecialchars($ticket['priority']) ?></td>
                <td><?= htmlspecialchars($ticket['customer_name']) ?></td>
                <td><?= htmlspecialchars($ticket['status']) ?></td>
                <td><?= $ticket['created_at'] ?></td>
                <td>
                    <select onchange="updateStatus(this, <?= $ticket['id'] ?>)">
                        <option value="Open" <?= $ticket['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
                        <option value="In Progress" <?= $ticket['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="Closed" <?= $ticket['status'] == 'Closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                    <span class="msg"></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function updateStatus(selectEl, ticketId) {
            const status = selectEl.value;
            const msg = selectEl.nextElementSibling;

            const formData = new FormData();
            formData.append("ticket_id", ticketId);
            formData.append("status", status);

            msg.textContent = "Saving...";
            msg.style.color = "gray";

            fetch("update_ticket_status.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        msg.textContent = "✔ Updated";
                        msg.style.color = "green";
                    } else {
                        msg.textContent = "✖ Failed";
                        msg.style.color = "red";
                    }
                })
                .catch(() => {
                    msg.textContent = "Error";
                    msg.style.color = "red";
                });
        }
    </script>

</body>

</html>