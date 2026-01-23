<?php
session_start();
include('../includes/header.php');
require '../config/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $conn = connection();

    $stmt = $conn->prepare(
        "SELECT * FROM customer_acc WHERE email = ?"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['pass'])) {
        echo ("Login successfull");
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role']; // store role in session

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php"); // admin dashboard
        } else {
            header("Location: user_dashboard.php"); // normal user dashboard
        }

        exit();
    } else {
        $error = "Invalid email or password";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Customer Support System Login</title>
</head>

<body>
    <main>
        <form action="" method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="email">Email</label><br>
            <input type="email" name="email" id="" required><br>
            <label for="password">Password</label><br>
            <input type="password" name="password" id="" required><br>
            <input type="submit">
            <p>Dont have an account?</p>
            <button type="button" onclick="window.location.href='register.php'">Register</button>
        </form>
    </main>
</body>

</html>