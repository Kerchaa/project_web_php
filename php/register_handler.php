<?php
// Database connection setup
include './db.php';

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cin = $_POST['cin'];
    $phone = $_POST['phone'];

    // Initialize error array
    $errors = array();

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email is not valid";
    }

    // Check if password contains at least one uppercase letter and one digit
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
        $errors['password'] = "Password must contain at least one uppercase letter and one digit";
    }

    // Check if CIN and phone are numeric
    if (!is_numeric($cin)) {
        $errors['cin'] = "CIN must be numeric";
    }

    if (!is_numeric($phone)) {
        $errors['phone'] = "Phone must be numeric";
    }

    // Check if name contains only letters and spaces
    if (!preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
        $errors['fullname'] = "Name must contain only letters and spaces";
    }

    // Check if email is already used
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors['email'] = "Email is already used";
    }

    $stmt->close();

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Prepare and execute the query
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, cin, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $fullname, $email, password_hash($password, PASSWORD_BCRYPT), $cin, $phone);

        if ($stmt->execute()) {
            // Set session variables
            $_SESSION['email'] = $email;
            $_SESSION['fullname'] = $fullname;

            // Redirect to profile.php
            header("Location: profile.php");
            exit();
        } else {
            echo "<div class='error'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        // Store errors in session
        $_SESSION['register_errors'] = $errors;

        // Redirect to register.php
        header("Location: register.php");
        exit();
    }

    $conn->close();
}
?>
