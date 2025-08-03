<?php
// admin.php
session_start();
require 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
$users = $pdo->query("SELECT * FROM users")->fetchAll();
$backgrounds = $pdo->query("SELECT * FROM background_images")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Fifteen Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    
    <?php
    // Show success message if redirected after upload
    if (isset($_GET['upload']) && $_GET['upload'] === 'success') {
        echo "<p style='color:green;'>Background uploaded successfully!</p>";
    }
    ?>

    <!-- Link to upload_bg.php -->
    <p>
        <a href="upload_bg.php">Upload New Background</a> |
        <a href="stats.php">View Leaderboards/Stats</a> |
        <a href="index.php">Back to Game</a>
    </p>

    <h3>Users</h3>
    <table border="1" style="margin:auto;">
        <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Registered</th></tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?=$u['user_id']?></td>
                <td><?=htmlspecialchars($u['username'])?></td>
                <td><?=htmlspecialchars($u['email'])?></td>
                <td><?=$u['role']?></td>
                <td><?=$u['registration_date']?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h3>Background Images</h3>
    <table border="1" style="margin:auto;">
        <tr><th>ID</th><th>Name</th><th>URL</th><th>Active</th></tr>
        <?php foreach ($backgrounds as $bg): ?>
            <tr>
                <td><?=$bg['image_id']?></td>
                <td><?=htmlspecialchars($bg['image_name'])?></td>
                <td><?=htmlspecialchars($bg['image_url'])?></td>
                <td><?=$bg['is_active'] ? 'Yes' : 'No'?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>