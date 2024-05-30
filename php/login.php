<?php
// Start session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RASKI Carpool Service</title>
    <link rel="stylesheet" href="../css/registerlog.css">
</head>
<body>
    <?php include '../html/navbar.html'; ?>

    <div class="container">
        <h2>Login</h2>
        <?php
        // Check if there are any login errors stored in session
        if (isset($_SESSION['login_errors'])) {
            $errors = $_SESSION['login_errors'];
            echo '<div class="error-container">';
            foreach ($errors as $error) {
                echo '<div class="error-message">';
                echo '<svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">';
                echo '<circle cx="16" cy="16" r="16" style="fill:#D72828;"/>';
                echo '<path d="M14.5,25h3v-3h-3V25z M14.5,6v13h3V6H14.5z" style="fill:#E6E6E6;"/>';
                echo '</svg>';
                echo $error;
                echo '</div>';
            }
            echo '</div>';
            // Unset the session variable to clear errors
            unset($_SESSION['login_errors']);
        }
        ?>
        <form action="login_handler.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="walter.white@yahoo.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="MethADDICT420" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
