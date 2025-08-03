<?php
// landing.php  – public home page with a single Play button
session_start();
$logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Fifteen Puzzle – Play</title>
  <link rel="stylesheet" href="style.css">
  <style>
      .play-box {margin:100px auto;width:300px;text-align:center;}
      .play-btn {padding:12px 40px;font-size:1.3em;cursor:pointer;}
  </style>
</head>
<body>
  <h1>Fifteen Puzzle</h1>
  <p class="description">
     Classic sliding puzzle. Rearrange the tiles into order as fast as you can!
  </p>

  <div class="play-box">
      <?php if ($logged_in): ?>
          <form action="game.php" method="get">
              <button class="play-btn">Play</button>
          </form>
          <p>Logged-in as <?=htmlspecialchars($_SESSION['username'])?>. 
             <a href="logout.php">Log out</a></p>
      <?php else: ?>
          <form action="login.php" method="get">
              <button class="play-btn">Play</button>
          </form>
          <p>Need an account? <a href="register.php">Register</a></p>
      <?php endif; ?>
  </div>
</body>
</html>