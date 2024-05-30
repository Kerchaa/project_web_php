<?php
// Start session
session_start();
// Include database connection
include './db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_errors'][] = 'Invalid email format.';
    } else {
        // Prepare and execute the query to fetch user with the given email
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Fetch user details
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, redirect to profile.php
                // Password is correct, add user information to session
                $_SESSION['email'] = $email;
                header("Location: profile.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['login_errors'][] = 'Incorrect password.';
            }
        } else {
            // User with given email does not exist
            $_SESSION['login_errors'][] = 'User with this email does not exist.';
        }
    }

    // Close prepared statement and database connection
    $stmt->close();
    $conn->close();

    // Redirect back to login page if there are errors
    header("Location: login.php");
    exit();
}
?>
