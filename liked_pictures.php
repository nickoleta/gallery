<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

// Redirect to index.php if the user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Logout logic
if (!empty($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Get username if logged in
$username = $_SESSION['username'] ?? '';
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch liked pictures
$likedPictures = getLikedPictures($user_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Liked Pictures</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>Liked Pictures</h1>
        </header>

        <div class="menu">
            <div class="menu-links">
                <a href="index.php">All Pictures</a>
                <a href="liked_pictures.php">Liked Pictures</a>                    
                <a href="my_pictures.php">My Pictures</a>
                <a href="upload.php">Upload Picture</a>
            </div>
            <div class="menu-logout">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="gallery-container" id="content">
            <div class="gallery">
                <table>
                    <tbody>
                        <?php
                        $rowCount = 0;
                        foreach ($likedPictures as $picture):
                            if ($rowCount % 4 == 0) {
                                echo "<tr>";
                            }
                            ?>
                        <td id="picture_<?php echo $picture['id']; ?>">
                            <div class="image">
                                <img src="images/<?php echo $picture['filename']; ?>" alt="Picture" class="thumbnail" onclick="openModal('<?php echo $picture['filename']; ?>', <?php echo $picture['id']; ?>)">
                                <?php if (!empty($username)): ?>
                                    <button class="like-btn liked" data-picture-id="<?php echo $picture['id']; ?>" onclick="toggleLike(this)">&#9829;</button>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php
                        $rowCount++;
                        if ($rowCount % 4 == 0) {
                            echo "</tr>";
                        }
                        endforeach;
                        // Ensure to close the last row if it's not complete
                        if ($rowCount % 4 != 0) {
                            echo "</tr>";
                        }
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
                                // Remove the picture from the DOM
                                const pictureElement = document.getElementById('picture_' + pictureId);
                                if (pictureElement) {
                                    pictureElement.remove();
                                }
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
                xhr.send('picture_id=' + pictureId + '&unlike=1');
            }
        </script>
    </body>
</html>
