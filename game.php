<?php
// game.php  – protected puzzle page
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: landing.php');
    exit;
}

require 'db.php'; 

// Fetch backgrounds for selection
$bgStmt = $pdo->query("SELECT * FROM background_images WHERE is_active = 1");
$backgrounds = $bgStmt->fetchAll();
$defaultBg = $backgrounds[0]['image_url'] ?? 'backgrounds/bg1.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fifteen Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (isset($_SESSION['username'])): ?>
        <div class="user-bar">
            Welcome, <?=htmlspecialchars($_SESSION['username'])?> |
            <a href="user_prefs.php">Preferences</a> |
            <a href="stats.php">Stats</a> |
            <a href="logout.php">Logout</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                | <a href="admin.php">Admin Dashboard</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="user-bar">
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        </div>
    <?php endif; ?>
    <h1>Fifteen Puzzle</h1>
    <p class="description">
        Rearrange the squares into numerical order by sliding them into the empty space.
    </p>
    <div>
        <label for="background-select">Background:</label>
        <select id="background-select">
            <?php foreach ($backgrounds as $bg): ?>
                <option value="<?=htmlspecialchars($bg['image_url'])?>"><?=htmlspecialchars($bg['image_name'])?></option>
            <?php endforeach; ?>
        </select>
        <label for="size-select">Size:</label>
        <select id="size-select">
            <option value="3">3x3</option>
            <option value="4" selected>4x4</option>
            <option value="5">5x5</option>
            <option value="6">6x6</option>
        </select>
    </div>
    <div id="puzzle-container"></div>
    <button id="shuffle-button">Shuffle</button>
    <button id="cheat-button">Cheat</button>
    <div id="game-info">
        <span id="timer">Time: 0s</span> | <span id="moves">Moves: 0</span>
        <span id="best-score"></span>
    </div>
    <div class="history">
        <p>
            The Fifteen Puzzle is a classic sliding puzzle invented in the 19th century. 
            Sam Loyd is often mistakenly credited with its invention.
        </p>
    </div>
    <div id="puzzle-container" role="grid" aria-label="Fifteen Puzzle board"></div>
    <div class="validators">
        <a href="https://validator.w3.org/" target="_blank">
            <img src="https://www.w3.org/Icons/valid-html401" alt="Valid HTML!" height="31" width="88">
        </a>
        <a href="https://jigsaw.w3.org/css-validator/" target="_blank">
            <img src="https://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" height="31" width="88">
        </a>
    </div>
    <div id="win-notification" style="display:none;">
        <h2>Congratulations! You solved the puzzle!</h2>
        <img src="https://media.giphy.com/media/111ebonMs90YLu/giphy.gif" alt="Win" style="width:200px;">
    </div>
    <audio id="bg-music" src="backgrounds/music.mp3" loop></audio>
    <script src="fifteen.js"></script>
</body>
</html>