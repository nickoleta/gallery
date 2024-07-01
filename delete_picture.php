<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['picture_id'])) {
    $picture_id = $_POST['picture_id'];

    // Fetch the picture to ensure it belongs to the user
    $picture = getPictureById($picture_id);

    if ($picture && $picture['user_id'] == $user_id) {
        // Delete the picture from the database and filesystem
        if (deletePicture($picture_id, $picture['filename'])) {
            header("Location: index.php");
            exit();
        } else {
            $error = 'Error deleting picture';
        }
    } else {
        $error = 'You do not have permission to delete this picture';
    }
} else {
    $error = 'Invalid request';
}

header("Location: my_pictures.php?error=" . urlencode($error));
exit();

function getPictureById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pictures WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deletePicture($id, $filename) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM pictures WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        // Delete file from filesystem
        return unlink('images/' . $filename);
    }
    return false;
}
?>
