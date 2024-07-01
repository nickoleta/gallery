<?php
session_start();
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
                <input type="text" id="username" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password">
            </div>
            <button type="submit">Register</button>
            <div class="login-redirect">
                <p>Already have an account?</p>
                <a href="login.php">Login here</a>
            </div>
        </form>
    </div>
</body>
</html>
