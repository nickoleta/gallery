<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My pictures</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>My pictures</h1>
        
    <?php if (isLoggedIn()): ?>
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
                    echo $username;
                    $rowCount++;
                    if ($rowCount % 4 == 0 || $rowCount == count($pictures)) echo "</tr>"; 
                endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>