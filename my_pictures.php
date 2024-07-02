<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

// Get username if logged in
$username = $_SESSION['username'] ?? '';
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch user-specific pictures
$pictures = getUserPictures($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Pictures</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>My Pictures</h1>
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
                            <img src="images/<?php echo $picture['filename']; ?>" alt="Picture" class="thumbnail" onclick="openModal(this.src)">
                            <form action="delete_picture.php" method="POST" class="delete-form">
                                <input type="hidden" name="picture_id" value="<?php echo $picture['id']; ?>">
                                <button type="button" class="delete" onclick="deletePicture(this)">Delete</button>
                            </form>
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
        function openModal(src) {
            document.getElementById('myModal').style.display = "block";
            document.getElementById('img01').src = src;
            document.getElementById('content').classList.add('blurred');
        }

        function closeModal() {
            document.getElementById('myModal').style.display = "none";
            document.getElementById('content').classList.remove('blurred');
        }

        function deletePicture(button) {
            const form = button.closest('.delete-form');
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', 'delete_picture.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        form.closest('td').remove();
                    } else {
                        console.error('Error:', response.message);
                    }
                } else {
                    console.error('Request failed. Returned status of ' + xhr.status);
                }
            };

            xhr.send(formData);
        }
    </script>
</body>
</html>
