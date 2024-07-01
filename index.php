<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

// Logout logic
if (!empty($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Get username if logged in
$username = $_SESSION['username'] ?? '';

$pictures = getAllPictures();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Gallery</h1>

    <?php if (!empty($username)): ?>
        <div class="logout">
            <form action="logout.php" method="POST">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
        <div class="menu">
            <a href="upload.php">Upload Picture</a>
            <a href="my_pictures.php">My Pictures</a>
            <a href="index.php">All Pictures</a>
            <a href="liked_pictures.php">Liked Pictures</a>
        </div>
    <?php else: ?>
        <div class="login">
            <a href="login.php">Login</a>
        </div>
    <?php endif; ?>

    <div class="gallery">
        <table>
            <tbody>
                <?php
                $rowCount = 0;
                foreach ($pictures as $picture): 
                    if ($rowCount % 4 == 0) echo "<tr>"; 
                ?>
                    <td>
                        <div class="image">
                            <img src="images/<?php echo $picture['filename']; ?>" alt="Picture">
                        </div>
                    </td>
                <?php
                    $rowCount++;
                    if ($rowCount % 4 == 0 || $rowCount == count($pictures)) echo "</tr>"; 
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
