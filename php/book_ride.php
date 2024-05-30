<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: register.php");
    exit();
}

// Fetch the user's ID based on the email stored in the session
$email = $_SESSION['email'];
$user_id = null;

$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if ($user_id === null) {
    echo "User ID not found for the email.";
    exit();
}

// Get ride ID from POST request
$ride_id = $_POST['ride_id'] ?? null;

if ($ride_id === null) {
    echo "Ride ID is required.";
    exit();
}

// Insert a new booking
$sql = "INSERT INTO bookings (user_id, ride_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $ride_id);
if ($stmt->execute()) {
    echo "Booking created successfully.";
} else {
    echo "Error creating booking: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect back to carpool.php
header("Location: carpool.php");
exit();
?>
