<?php
// register.php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, registration_date) VALUES (?, ?, ?, NOW())");
    try {
        $stmt->execute([$username, $email, $password]);
        header('Location: login.php?registered=1');
        exit;
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Fifteen Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input name="username" placeholder="Username" required><br>
        <input name="email" type="email" placeholder="Email" required><br>
        <input name="password" type="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</body>
</html>