<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = getLoggedInUserId();
    if ($userId && isset($_FILES['picture'])) {
        $filename = $_FILES['picture']['name'];
        $filepath = 'images/' . $filename;
        
        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $filepath)) {
            if (uploadPicture($filename, $userId)) {
                echo "Picture uploaded successfully.";
            } else {
                echo "Failed to save picture in database.";
            }
        } else {
            echo "Failed to upload picture.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Picture</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="upload-container">
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <h2>Upload Picture</h2>
            <?php if (!empty($errors)): ?>
                <div>
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <input type="file" id="picture" name="picture" required>
            </div>
            <button class="upload" type="submit">Upload</button>
            <div class="upload-redirect">
                <p></p>
                <a href="index.php">Back to Gallery</a>
            </div>
        </form>
    </div>
</body>
</html>
