<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connection();

$stmt = $conn->prepare(
    "DELETE FROM tickets WHERE id = ? AND user_id = ?"
);
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);

header("Location: user_dashboard.php");
exit();
