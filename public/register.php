<?php
session_start();
include('../includes/header.php');
require '../config/db.php';

$conn = connection();

$error = '';
$success = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid request. Please try again.";
    } else {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare(
                "INSERT INTO customer_acc (name, email, pass) VALUES (?, ?, ?)"
            );
            $stmt->execute([$name, $email, $password]);

            $success = "Registration successful. Please login.";
        } catch (PDOException $e) {

            // Duplicate email (UNIQUE constraint)
            if ($e->getCode() == 23000) {
                $error = "Email already exists. Please login instead.";
            } else {
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Register</title>
</head>

<body>
    <main>
        <form method="post" autocomplete="off">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <?php if (!empty($error)) : ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <p class="success"><?= $success; ?></p>
            <?php endif; ?>

            <label>Email</label><br>
            <input type="email" name="email" required><br>

            <label>Username</label><br>
            <input type="text" name="name" required><br>

            <label>Password</label><br>
            <input type="password" name="password" required><br>

            <input type="submit" value="Register">

            <p>Already have an account?</p>
            <button type="button" onclick="window.location.href='login.php'">Login</button>

        </form>
    </main>
</body>

</html>