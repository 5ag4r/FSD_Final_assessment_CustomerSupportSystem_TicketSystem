<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare(
        "INSERT INTO tickets (user_id, subject, issue_type, priority, description)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['subject'],
        $_POST['issue_type'],
        $_POST['priority'],
        $_POST['description']
    ]);

    header("Location: user_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Ticket</title>
</head>

<body>

    <h2>Add Ticket</h2>

    <form method="post">
        <input name="subject" placeholder="Subject" required><br><br>
        <input name="issue_type" placeholder="Issue Type" required><br><br>

        <select name="priority">
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
        </select><br><br>

        <textarea name="description" placeholder="Describe your issue"></textarea><br><br>

        <button type="submit">Submit Ticket</button>
    </form>

    <a href="dashboard.php">⬅ Back</a>

</body>

</html>