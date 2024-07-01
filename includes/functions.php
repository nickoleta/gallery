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
    $stmt = $conn->prepare("INSERT INTO pictures (filename, user_id) VALUES (?, ?)");
    return $stmt->execute([$filename, $userId]);
}

?>