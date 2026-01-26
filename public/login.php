<?php
session_start();
include('../includes/header.php');
require '../config/db.php';

$error = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid request.";
    } else {

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $conn = connection();

        $stmt = $conn->prepare("SELECT * FROM customer_acc WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pass'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
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
    <title>Login</title>
</head>

<body>
    <main>
        <form method="post" autocomplete="off">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <?php if (!empty($error)) : ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>

            <label>Email</label><br>
            <input type="email" name="email" required><br>

            <label>Password</label><br>
            <input type="password" name="password" required><br>

            <input type="submit" value="Login">

            <p>Don't have an account?</p>
            <button type="button" onclick="window.location.href='register.php'">Register</button>

        </form>
    </main>
</body>

</html>