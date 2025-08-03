<?php
require 'db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?")->execute([$user['user_id']]);
        header('Location: game.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Fifteen Puzzle</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #89f7fe, #66a6ff);
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #333;
    }
    .form-container {
      background: white;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 90%;
      text-align: center;
    }
    h2 {
      margin-bottom: 24px;
      color: #004080;
    }
    input[type=text], input[type=password] {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1em;
      box-sizing: border-box;
      transition: border-color 0.3s ease;
    }
    input[type=text]:focus, input[type=password]:focus {
      border-color: #007BFF;
      outline: none;
    }
    button {
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 14px 0;
      width: 100%;
      font-size: 1.2em;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #0056b3;
    }
    a {
      color: #007BFF;
      text-decoration: none;
      display: inline-block;
      margin-top: 20px;
      font-weight: 600;
    }
    a:hover {
      text-decoration: underline;
    }
    .error-msg {
      color: #e63946;
      margin-bottom: 20px;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
      <p class="error-msg"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>
    <form method="post" novalidate>
      <input type="text" name="username" placeholder="Username" required autofocus>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <a href="register.php">Don't have an account? Register</a>
  </div>
</body>
</html>
