<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RASKI Carpool Service</title>
    <link rel="stylesheet" href="../css/registerlog.css">
</head>
<body>
    <?php include '../html/navbar.html'; ?>

    <div class="container">
        <h2>Register</h2>
        <?php
        // Check if there are any register errors stored in session
        if (isset($_SESSION['register_errors'])) {
            $errors = $_SESSION['register_errors'];
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
            unset($_SESSION['register_errors']);
        }
        ?>
        <form action="register_handler.php" method="post">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Walter White" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="walter.white@yahoo.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="MethADDICT420" required>
            </div>
            <div class="form-group">
                <label for="cin">CIN</label>
                <input type="text" id="cin" name="cin" placeholder="10 101 375" maxlength="8" pattern="\d{8}" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" placeholder="94 486 605" maxlength="8" pattern="\d{8}" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
