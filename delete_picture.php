<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

// Check if user is logged in and if picture_id is provided
if (!isLoggedIn() || !isset($_POST['picture_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$user_id = getLoggedInUserId();
$picture_id = $_POST['picture_id'];

// Check if the picture belongs to the logged-in user
if ($user_id != getPictureUserId($picture_id, $conn)) {
    echo json_encode(['status' => 'error', 'message' => 'You can only delete your own pictures']);
    exit();
}

try {
    // Begin transaction
    $conn->beginTransaction();

    // Delete all likes associated with the picture
    $stmt = $conn->prepare("DELETE FROM likes WHERE picture_id = :picture_id");
    $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
    $stmt->execute();

    // Get the filename of the picture to delete the file from the server
    $stmt = $conn->prepare("SELECT filename FROM pictures WHERE id = :picture_id");
    $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
    $stmt->execute();
    $picture = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($picture) {
        $filename = 'images/' . $picture['filename'];

        // Delete the picture record from the database
        $stmt = $conn->prepare("DELETE FROM pictures WHERE id = :picture_id");
        $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Delete the file from the server
        if (file_exists($filename)) {
            unlink($filename);
        }

        echo json_encode(['status' => 'success', 'message' => 'Picture deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Picture not found']);
    }
} catch (PDOException $e) {
    // Rollback transaction if there is an error
    $conn->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete picture: ' . $e->getMessage()]);
}
?>
