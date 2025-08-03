<?php
// stats.php
session_start();
require 'db.php';

/* --- Fetch overall best times/moves for each puzzle size --- */
$global = $pdo->query("
    SELECT puzzle_size,
           MIN(time_taken_seconds)  AS best_time,
           MIN(moves_count)         AS best_moves
    FROM   game_stats
    WHERE  win_status = 1
    GROUP  BY puzzle_size
")->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);

/* --- If logged-in, fetch this user’s personal bests --- */
$userBest = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("
        SELECT puzzle_size,
               MIN(time_taken_seconds)  AS best_time,
               MIN(moves_count)         AS best_moves
        FROM   game_stats
        WHERE  win_status = 1
          AND  user_id = ?
        GROUP  BY puzzle_size
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $userBest = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboards – Fifteen Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Global Leaderboard</h2>
<table border="1" style="margin:auto;">
    <tr><th>Size</th><th>Fastest Time (s)</th><th>Fewest Moves</th></tr>
    <?php foreach ($global as $size => $row): ?>
        <tr>
            <td><?=htmlspecialchars($size)?></td>
            <td><?=$row['best_time']?></td>
            <td><?=$row['best_moves']?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($userBest): ?>
    <h2>Your Personal Bests</h2>
    <table border="1" style="margin:auto;">
        <tr><th>Size</th><th>Fastest Time (s)</th><th>Fewest Moves</th></tr>
        <?php foreach ($userBest as $size => $row): ?>
            <tr>
                <td><?=htmlspecialchars($size)?></td>
                <td><?=$row['best_time']?></td>
                <td><?=$row['best_moves']?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (!isset($_SESSION['user_id'])): ?>
    <p style="text-align:center;">Log in to see your personal stats.</p>
<?php endif; ?>

<p style="text-align:center;"><a href="game.php">Back to Game</a></p>
</body>
</html>