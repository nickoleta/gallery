<?php
session_start();

function registerUser($username, $password) {
    global $conn;

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['errors'] = ["User with username $username already exists!"];
        return false; 
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_hash);

    if ($stmt->execute()) {
        return getUserByUsername($username);
    }
    return false;
}

function getUserByUsername($username) {
    global $conn;

    $query = "SELECT id, username FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function loginUser($username, $password) {
    global $conn;

    $query = "SELECT id, username, password FROM users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            return $user; // Return the user data
        }
    }
    return false;
}

function getLoggedInUserId() {
    return $_SESSION['user_id'] ?? null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getAllPictures() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pictures");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function uploadPicture($filename, $userId) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO pictures (filename, user_id) VALUES (:filename, :user_id)");
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getUserPictures($user_id) {
    global $conn; 

    $sql = "SELECT * FROM pictures WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function userHasLiked($user_id, $picture_id, $conn) {
    try {
        $sql = "SELECT * FROM likes WHERE user_id = :user_id AND picture_id = :picture_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result !== false;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function addLike($user_id, $picture_id, $conn) {
    if (!userHasLiked($user_id, $picture_id, $conn)) {
        $sql = "INSERT INTO likes (user_id, picture_id) VALUES (:user_id, :picture_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    return false;
}

function removeLike($user_id, $picture_id, $conn) {
    $sql = "DELETE FROM likes WHERE user_id = :user_id AND picture_id = :picture_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
    return $stmt->execute();
}

function getPictureUserId($picture_id, $conn) {
    $sql = "SELECT user_id FROM pictures WHERE id = :picture_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':picture_id', $picture_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['user_id'];
}

function getLikedPictures($user_id, $conn) {
    $sql = "
        SELECT p.* 
        FROM pictures p
        INNER JOIN likes l ON p.id = l.picture_id
        WHERE l.user_id = :user_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>