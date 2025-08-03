<?php
// upload_bg.php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bg_image'])) {
    $name = trim($_POST['image_name']);
    $file = $_FILES['bg_image'];
    $target_dir = "backgrounds/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowed) && $file['size'] < 2 * 1024 * 1024) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO background_images (image_name, image_url, is_active, uploaded_by_user_id) VALUES (?, ?, 1, ?)");
            $stmt->execute([$name, $target_file, $_SESSION['user_id']]);
            header('Location: admin.php?upload=success');
            exit;
        } else {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "Invalid file type or size.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Background - Fifteen Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Upload New Background Image</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" enctype="multipart/form-data">
        <input name="image_name" placeholder="Image Name" required><br>
        <input type="file" name="bg_image" accept="image/*" required><br>
        <button type="submit">Upload</button>
    </form>
    <a href="admin.php">Back to Admin Dashboard</a>
</body>
</html>