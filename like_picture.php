<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include 'includes/db.php';
include 'includes/functions.php';

// Validate session and input
if (!isset($_SESSION['user_id']) || !isset($_POST['picture_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['user_id'];
$picture_id = $_POST['picture_id'];

// Check if user is trying to like their own picture
if ($user_id == getPictureUserId($picture_id, $conn)) {
    echo json_encode(['status' => 'error', 'message' => 'Cannot like your own picture']);
    exit();
}

// Toggle like status
if (userHasLiked($user_id, $picture_id, $conn)) {
    if (removeLike($user_id, $picture_id, $conn)) {
        echo json_encode(['status' => 'success', 'liked' => false]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove like']);
    }
} else {
    if (addLike($user_id, $picture_id, $conn)) {
        echo json_encode(['status' => 'success', 'liked' => true]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add like']);
    }
}
?>
