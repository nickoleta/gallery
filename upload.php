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
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <h2>Upload Picture</h2>
            <div class="form-group">
                <label for="picture">Select Picture</label>
                <input type="file" id="picture" name="picture" required>
            </div>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
