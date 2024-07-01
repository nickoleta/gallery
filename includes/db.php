<?php
$servername = "localhost";
$username = "mygallery";
$password = "gallery123!";
$dbname = "gallery_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: ".$e->getMessage();
}
?>