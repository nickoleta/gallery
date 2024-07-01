<?php
session_start();

$username = $_SESSION['username'];
$password = $_SESSION['password'];

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    if (isset($_POST['username'])) {
//        $username = $_POST['username'];
//    }
//    if (isset($_POST['password'])) {
//        $password = $_POST['password'];
//    }
//}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="register-container">
        <form action="register_handler.php" method="POST" class="register-form">
            <h2>Register</h2>
            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="error-message">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
