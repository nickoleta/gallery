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
                                <img src="images/<?php echo $picture['filename']; ?>" alt="Picture" class="thumbnail" onclick="openModal('<?php echo $picture['filename']; ?>', <?php echo $picture['id']; ?>)">
                                <?php if (!empty($username) && $picture['user_id'] == $_SESSION['user_id']): ?>
                                    <form action="delete_picture.php" method="POST" class="delete-form">
                                        <input type="hidden" name="picture_id" value="<?php echo $picture['id']; ?>">
                                        <button type="submit" class="delete">Delete</button>
                                    </form>
                                <?php endif; ?>
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
        </div>

        <script>
            function openModal(src, id) {
                document.getElementById('myModal').style.display = "block";
                document.getElementById('img01').src = 'images/' + src;
                document.getElementById('content').classList.add('blurred');
            }

            function closeModal() {
                document.getElementById('myModal').style.display = "none";
                document.getElementById('content').classList.remove('blurred');
            }
        </script>
    </body>
</html>
