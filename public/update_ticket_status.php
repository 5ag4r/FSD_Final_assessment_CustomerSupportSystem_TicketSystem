<?php
session_start();
require '../config/db.php';

header('Content-Type: application/json');

// Admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

// Validate input
if (!isset($_POST['ticket_id'], $_POST['status'])) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request"
    ]);
    exit;
}

$conn = connection();

$ticket_id = $_POST['ticket_id'];
$status = $_POST['status'];

// Optional: whitelist statuses
$allowed = ['Open', 'In Progress', 'Closed'];
if (!in_array($status, $allowed)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid status"
    ]);
    exit;
}

$stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ?");
$result = $stmt->execute([$status, $ticket_id]);

if ($result) {
    echo json_encode([
        "success" => true,
        "message" => "Status updated"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Database error"
    ]);
}
