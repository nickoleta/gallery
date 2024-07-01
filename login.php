<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

$username = '';
if (isset($_GET['username'])) {
    $username = $_GET['username'];
}
$password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (($user = loginUser($username, $password))!=false) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $errors[] = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="POST" class="login-form">
            <h2>Login</h2>
            <?php if (!empty($errors)): ?>
                <div>
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button class="login" type="submit">Login</button>
            <div class="register-redirect">
                <p>Don't have an account?</p>
                <a href="register.php">Register here</a>
            </div>
        </form>
    </div>
</body>
</html>
