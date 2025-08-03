<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Fifteen Puzzle – Play</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
<style>
  body {
    margin: 0; padding: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #89f7fe, #66a6ff);
    height: 100vh;
    display: flex; justify-content: center; align-items: center;
    color: #333;
  }
  .play-box {
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 400px;
    width: 90%;
  }
  h1 {
    margin-top: 0;
    color: #004080;
  }
  .description {
    font-size: 1.1em;
    margin-bottom: 30px;
    color: #444;
  }
  .play-btn {
    background: #007BFF;
    border: none;
    color: white;
    padding: 14px 50px;
    font-size: 1.2em;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .play-btn:hover {
    background-color: #0056b3;
  }
  a {
    color: #007BFF;
    text-decoration: none;
  }
  a:hover {
    text-decoration: underline;
  }
  p {
    margin-top: 20px;
  }
</style>
</head>
<body>
  <div class="play-box">
    <h1>Fifteen Puzzle</h1>
    <p class="description">
      Classic sliding puzzle – rearrange the tiles as fast as you can!
    </p>
    <?php if ($logged_in): ?>
      <form action="game.php" method="get">
        <button class="play-btn">Play</button>
      </form>
      <p>Logged in as <strong><?=htmlspecialchars($_SESSION['username'])?></strong>. <a href="logout.php">Log out</a></p>
    <?php else: ?>
      <form action="login.php" method="get">
        <button class="play-btn">Play</button>
      </form>
      <p>New User? <a href="register.php">Register here</a></p>
    <?php endif; ?>
  </div>
</body>
</html>
