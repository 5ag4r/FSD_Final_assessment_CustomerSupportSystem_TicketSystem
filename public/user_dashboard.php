<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connection();

// Fetch only logged-in user's tickets
$stmt = $conn->prepare(
    "SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
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

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background: #f5f5f5;
        }

        a {
            margin-right: 10px;
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
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
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h1>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </header>

    <a href="add_ticket.php">➕ Add New Ticket</a>
    <br><br>

    <table>
        <tr>
            <th>Subject</th>
            <th>Issue Type</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if ($tickets): ?>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['subject']) ?></td>
                    <td><?= htmlspecialchars($t['issue_type']) ?></td>
                    <td><?= htmlspecialchars($t['priority']) ?></td>
                    <td><?= htmlspecialchars($t['status']) ?></td>
                    <td>
                        <a href="edit_ticket.php?id=<?= $t['id'] ?>">Edit</a>
                        <a href="delete_ticket.php?id=<?= $t['id'] ?>"
                            onclick="return confirm('Delete this ticket?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No tickets yet</td>
            </tr>
        <?php endif; ?>

    </table>

</body>

</html>