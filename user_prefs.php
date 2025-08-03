<?php
// user_prefs.php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

/* --- Fetch all active backgrounds for dropdown --- */
$bgRows = $pdo->query("
    SELECT image_id, image_name
    FROM   background_images
    WHERE  is_active = 1
")->fetchAll();

/* --- Load existing prefs or create row on first visit --- */
$uid  = $_SESSION['user_id'];
$prefs = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
$prefs->execute([$uid]);
$prefs = $prefs->fetch();

if (!$prefs) {               // create default row if not present
    $pdo->prepare("INSERT INTO user_preferences (user_id) VALUES (?)")->execute([$uid]);
    $prefs = ['default_puzzle_size'=>'4x4','preferred_background_image_id'=>null,
              'sound_enabled'=>1,'animations_enabled'=>1];
}

/* --- Handle form submit --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $size   = $_POST['default_puzzle_size'];
    $bgID   = $_POST['preferred_background_image_id'] ?: null;
    $sound  = isset($_POST['sound_enabled']) ? 1 : 0;
    $anim   = isset($_POST['animations_enabled']) ? 1 : 0;
    $stmt = $pdo->prepare("
        UPDATE user_preferences
        SET default_puzzle_size = ?, preferred_background_image_id = ?,
            sound_enabled = ?, animations_enabled = ?
        WHERE user_id = ?
    ");
    $stmt->execute([$size,$bgID,$sound,$anim,$uid]);
    header('Location: user_prefs.php?saved=1');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Preferences – Fifteen Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Your Game Preferences</h2>

<?php if (isset($_GET['saved'])): ?>
    <p style="color:green;text-align:center;">Preferences saved.</p>
<?php endif; ?>

<form method="post" style="text-align:center;">
    <label>
        Default Puzzle Size:
        <select name="default_puzzle_size">
            <?php foreach (['3x3','4x4','5x5','6x6'] as $sz): ?>
                <option value="<?=$sz?>" <?=$prefs['default_puzzle_size']===$sz?'selected':''?>><?=$sz?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>
        Preferred Background:
        <select name="preferred_background_image_id">
            <option value="">(random)</option>
            <?php foreach ($bgRows as $row): ?>
                <option value="<?=$row['image_id']?>" <?=$prefs['preferred_background_image_id']==$row['image_id']?'selected':''?>>
                    <?=htmlspecialchars($row['image_name'])?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label><input type="checkbox" name="sound_enabled" <?=$prefs['sound_enabled']?'checked':''?>> Enable Sound</label><br>
    <label><input type="checkbox" name="animations_enabled" <?=$prefs['animations_enabled']?'checked':''?>> Enable Animations</label><br><br>

    <button type="submit">Save Preferences</button>
</form>

<p style="text-align:center;"><a href="index.php">Back to Game</a></p>
</body>
</html>