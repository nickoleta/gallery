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
$user_id = $_SESSION['user_id'] ?? '';

// Fetch all pictures
$pictures = getAllPictures();
?>
<!DOCTYPE html>
<?php
// Same as before, fetch pictures and user details
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
                            $liked = userHasLiked($user_id, $picture['id'], $conn); // Check if user has liked this picture
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
                                <?php if (!empty($username) && $picture['user_id'] != $_SESSION['user_id']): ?>
                                    <button class="like-btn <?php echo $liked ? 'liked' : ''; ?>" data-picture-id="<?php echo $picture['id']; ?>" onclick="toggleLike(this)">&#9829;</button>
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

            function toggleLike(button) {
                const pictureId = button.getAttribute('data-picture-id');
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'like_picture.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                button.classList.toggle('liked', response.liked);
                            } else {
                                console.error('Error:', response.message);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.error('Response:', xhr.responseText);
                        }
                    } else {
                        console.error('Request failed. Returned status of ' + xhr.status);
                    }
                };

                // Check if the button is already liked or not
                const isLiked = button.classList.contains('liked');
                xhr.send('picture_id=' + pictureId + '&unlike=' + (isLiked ? '1' : '0'));
            }
        </script>
    </body>
</html>

