<?php
session_start();
include('../includes/header.php');

require '../config/db.php';
$conn = connection();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    try {
        $stmt = $conn->prepare("INSERT INTO customer_acc (name, email, pass)
	  	VALUES (?, ?, ?)");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $password);

        $stmt->execute();
        echo "Data inserted successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Customer Support System Register</title>
</head>

<body>
    <main>
        <form action="" method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="email">Enter your Email</label><br>
            <input type="email" name="email" required><br>
            <label for="name">User Name</label><br>
            <input type="text" name="name" id="" required><br>
            <label for="password">Password</label><br>
            <input type="password" name="password" id="" required><br>
            <input type="submit">
            <p>Already have an account?</p>
            <button type="button" onclick="window.location.href='login.php'">Login</button>
        </form>
    </main>
</body>

</html>