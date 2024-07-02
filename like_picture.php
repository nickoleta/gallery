<?php
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

// Check if we are liking or unliking
$unlike = isset($_POST['unlike']) && $_POST['unlike'] == '1';

if ($unlike) {
    // Remove like
    if (removeLike($user_id, $picture_id, $conn)) {
        echo json_encode(['status' => 'success', 'liked' => false]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to unlike picture']);
    }
} else {
    // Add like
    if (addLike($user_id, $picture_id, $conn)) {
        echo json_encode(['status' => 'success', 'liked' => true]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to like picture']);
    }
}
?>
