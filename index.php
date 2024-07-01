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
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch all pictures
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
    <header>
        <h1>Gallery</h1>
    </header>

    <?php if (!empty($username)): ?>
        <div class="menu">
            <div class="menu-links">
                <a href="upload.php">Upload Picture</a>
                <a href="my_pictures.php">My Pictures</a>
                <a href="index.php">All Pictures</a>
                <a href="liked_pictures.php">Liked Pictures</a>
            </div>
            <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="menu">
            <div class="login">
                <a href="login.php">Login</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="gallery-container" id="content">
        <div class="gallery">
            <table>
                <tbody>
                    <?php
                    $rowCount = 0;
                    foreach ($pictures as $picture): 
                        if ($rowCount % 4 == 0)
                            echo "<tr>";
                        ?>
                    <td>
                        <div class="image">
                            <img src="images/<?php echo $picture['filename']; ?>" alt="Picture" class="thumbnail" onclick="openModal(this.src, <?php echo $picture['id']; ?>, <?php echo $user_id == $picture['user_id'] ? 'true' : 'false'; ?>)">
                        </div>
                    </td>
                    <?php
                    $rowCount++;
                    if ($rowCount % 4 == 0 || $rowCount == count($pictures))
                        echo "</tr>";
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
        <form id="deleteForm" action="delete_picture.php" method="POST" style="display:none;">
            <input type="hidden" id="pictureId" name="picture_id">
            <button type="submit" class="delete">Delete</button>
        </form>
    </div>

    <script>
        function openModal(src, pictureId, canDelete) {
            document.getElementById('myModal').style.display = "block";
            document.getElementById('img01').src = src;
            document.getElementById('content').classList.add('blurred');
            if (canDelete) {
                document.getElementById('deleteForm').style.display = "block";
                document.getElementById('pictureId').value = pictureId;
            } else {
                document.getElementById('deleteForm').style.display = "none";
            }
        }

        function closeModal() {
            document.getElementById('myModal').style.display = "none";
            document.getElementById('content').classList.remove('blurred');
        }
    </script>
</body>
</html>
