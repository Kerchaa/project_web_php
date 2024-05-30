<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $seats_available = $_POST['seats_available'];
    // If session id is not set
if (!isset($_SESSION['id'])) {
    // Include database connection
    include 'db.php';

    // Get session email
    $session_email = $_SESSION['email'];

    // Prepare and execute query to get id where email matches session email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $session_email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If result is found, set session id
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['id'] = $user['id'];
    }

    // Close statement and database connection
    $stmt->close();
}

    $driver_id = $_SESSION['id'];
    var_dump($_SESSION);

    // Prepare and execute the query to add itinerary
    $stmt = $conn->prepare("INSERT INTO rides (driver_id, origin, destination, departure_time, seats_available) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $driver_id, $origin, $destination, $departure_time, $seats_available);

    if ($stmt->execute()) {
        // Itinerary added successfully
        header("Location: profile.php");
        exit();
    } else {
        // Error occurred
        $_SESSION['add_errors'][] = 'Error adding itinerary.';
        header("Location: add.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
