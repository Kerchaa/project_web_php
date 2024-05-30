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

// Get search parameters from POST request
$search_from = $_POST['search_from'] ?? '';
$search_to = $_POST['search_to'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

// Fetch rides based on search parameters and user ID
$sql = "SELECT * FROM rides WHERE driver_id != ? AND origin LIKE ? AND destination LIKE ? AND departure_time BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$like_search_from = "%{$search_from}%";
$like_search_to = "%{$search_to}%";
$stmt->bind_param("issss", $user_id, $like_search_from, $like_search_to, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Store the results in session to display in carpool.php
$rides = [];
while ($row = $result->fetch_assoc()) {
    $rides[] = $row;
}
$_SESSION['rides'] = $rides;

// Store search parameters in session to keep them on the form
$_SESSION['search_from'] = $search_from;
$_SESSION['search_to'] = $search_to;
$_SESSION['search_date'] = $start_date . ' to ' . $end_date;

$stmt->close();
$conn->close();

// Redirect back to carpool.php to display results
header("Location: carpool.php");
exit();
?>
