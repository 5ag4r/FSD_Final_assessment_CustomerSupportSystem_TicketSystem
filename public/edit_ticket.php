<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connection();

$id = $_GET['id'];

// Fetch ticket (only user's ticket)
$stmt = $conn->prepare(
    "SELECT * FROM tickets WHERE id = ? AND user_id = ?"
);
$stmt->execute([$id, $_SESSION['user_id']]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("Ticket not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare(
        "UPDATE tickets SET subject=?, issue_type=?, priority=?, description=?
         WHERE id=? AND user_id=?"
    );
    $stmt->execute([
        $_POST['subject'],
        $_POST['issue_type'],
        $_POST['priority'],
        $_POST['description'],
        $id,
        $_SESSION['user_id']
    ]);

    header("Location: user_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Ticket</title>
</head>

<body>

    <h2>Edit Ticket</h2>

    <form method="post">
        <input name="subject" value="<?= htmlspecialchars($ticket['subject']) ?>"><br><br>
        <input name="issue_type" value="<?= htmlspecialchars($ticket['issue_type']) ?>"><br><br>

        <select name="priority">
            <option <?= $ticket['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
            <option <?= $ticket['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
            <option <?= $ticket['priority'] == 'High' ? 'selected' : '' ?>>High</option>
        </select><br><br>

        <textarea name="description"><?= htmlspecialchars($ticket['description']) ?></textarea><br><br>

        <button>Update</button>
    </form>

    <a href="user_dashboard.php">⬅ Back</a>

</body>

</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        padding: 30px;
    }

    h2 {
        margin-bottom: 20px;
    }

    form {
        background: #fff;
        padding: 20px;
        width: 350px;
        border: 1px solid #ddd;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    textarea {
        height: 90px;
        resize: vertical;
    }

    button {
        padding: 8px 16px;
        background: #333;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background: #555;
    }

    a {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #333;
    }
</style>