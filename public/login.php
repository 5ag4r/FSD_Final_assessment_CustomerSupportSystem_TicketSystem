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

<style>
    /* Page setup */
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "Istok Web", sans-serif;
        flex-direction: column;
    }

    /* Form container */
    main {
        background: #ffffff;
        padding: 2.5rem;
        width: 100%;
        max-width: 380px;
        border-radius: 14px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    /* Heading */
    header h1 {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #333;
        font-size: 3rem;
    }

    /* Form */
    form {
        display: flex;
        flex-direction: column;
    }

    /* Labels */
    label {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 0.3rem;
    }

    /* Inputs */
    input[type="email"],
    input[type="password"] {
        padding: 0.75rem;
        margin-bottom: 1.2rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
    }

    /* Submit button */
    input[type="submit"] {
        background: #667eea;
        color: #fff;
        border: none;
        padding: 0.8rem;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    input[type="submit"]:hover {
        background: #5a67d8;
        transform: translateY(-1px);
    }

    /* Register text */
    form p {
        text-align: center;
        margin: 1.2rem 0 0.5rem;
        font-size: 0.85rem;
        color: #666;
    }

    /* Register button */
    button {
        background: transparent;
        border: 1px solid #667eea;
        color: #667eea;
        padding: 0.6rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    button:hover {
        background: #667eea;
        color: #fff;
    }

    .error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.7rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        text-align: center;
    }
</style>