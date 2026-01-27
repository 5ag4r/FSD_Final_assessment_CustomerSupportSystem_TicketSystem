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
        padding: 2.8rem;
        width: 100%;
        max-width: 420px;
        border-radius: 16px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
    }

    /* Title */
    header h2 {
        text-align: center;
        font-size: 1.9rem;
        margin-bottom: 1.8rem;
        color: #333;
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
        margin-bottom: 0.35rem;
    }

    /* Inputs */
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        padding: 0.8rem;
        margin-bottom: 1.3rem;
        border: 1px solid #ddd;
        border-radius: 9px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
    }

    /* Register button */
    input[type="submit"] {
        background: #667eea;
        color: #fff;
        border: none;
        padding: 0.85rem;
        border-radius: 10px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    input[type="submit"]:hover {
        background: #5a67d8;
        transform: translateY(-1px);
    }


    /* Error message */
    .error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.7rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .success {
        background: #cafab8;
        color: #46f337;
        padding: 0.7rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        text-align: center;
    }

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
</style>