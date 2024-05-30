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

// Retrieve user information
$user_email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT id, fullname, email, cin, phone FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// If the user data is not retrieved, redirect to login page
if (!$user) {
    header("Location: login.php");
    exit();
}

// Retrieve user's drives
$stmt = $conn->prepare("SELECT id, origin, destination, departure_time, seats_available FROM rides WHERE driver_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$drives_result = $stmt->get_result();
$drives = $drives_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Retrieve bookings where the user is booked and booking is approved
$stmt = $conn->prepare("SELECT rides.id, origin, destination, departure_time, seats_available, booking_approved FROM rides INNER JOIN bookings ON rides.id = bookings.ride_id WHERE bookings.user_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle approve booking request
if (isset($_POST['approve_booking'])) {
    $booking_id = $_POST['booking_id'];
    $stmt = $conn->prepare("UPDATE bookings SET booking_approved = true WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();
    header("Location: profile.php");
    exit();
}

// Handle remove booking request
if (isset($_POST['remove_booking'])) {
    $booking_id = $_POST['booking_id'];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();
    header("Location: profile.php");
    exit();
}

// Close database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - RASKI Carpool Service</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
    <?php include '../html/navbar.html'; ?>

    <div class="container">
        <div class="profile-info">
            <h2>User Information</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <td><?php echo $user['fullname']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $user['email']; ?></td>
                </tr>
                <tr>
                    <th>CIN</th>
                    <td><?php echo $user['cin']; ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $user['phone']; ?></td>
                </tr>
            </table>
            <a href="add.php" class="add-button">Add New Itinerary</a>
        </div>
        <div class="profile-rides">
            <div class="rides">
                <h2>Drives</h2>
                <?php foreach ($drives as $drive): ?>
                    <div class="drive">
                        <h3><?php echo htmlspecialchars($drive['origin']) . ' to ' . htmlspecialchars($drive['destination']); ?></h3>
                        <p>Departure Time: <?php echo htmlspecialchars($drive['departure_time']); ?> - Seats Available: <?php echo htmlspecialchars($drive['seats_available']); ?></p>
                        <ul>
                            <?php
                            // Fetch bookings for this drive
                            $stmt = $conn->prepare("SELECT users.fullname, users.phone, bookings.id FROM bookings INNER JOIN users ON bookings.user_id = users.id WHERE bookings.ride_id = ?");
                            $stmt->bind_param("i", $drive['id']);
                            $stmt->execute();
                            $bookings_result = $stmt->get_result();

                            // Display each booking and buttons for approval or removal
                            while ($booking = $bookings_result->fetch_assoc()): ?>
                                <li>
                                    Name: <?php echo htmlspecialchars($booking['fullname']); ?> - Phone: <?php echo htmlspecialchars($booking['phone']); ?>
                                    <?php //if (!$booking['booking_approved']): ?>
                                        <form action="profile.php" method="post">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <button type="submit" name="approve_booking">Approve</button>
                                        </form>
                                    <?php //endif; ?>
                                    <form action="profile.php" method="post">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="remove_booking">Remove</button>
                                    </form>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="rides">
                <h2>Bookings</h2>
                <ul>
                    <?php foreach ($bookings as $booking): ?>
                        <li>
                            Status: <?php echo ($booking['booking_approved'] ? '<span class="checkmark">&#10003;</span>' : '<span class="cross">&#10007;</span>'); ?>
                            <?php echo htmlspecialchars($booking['origin']) . ' to ' . htmlspecialchars($booking['destination']) . ' - Departure Time: ' . htmlspecialchars($booking['departure_time']) . ' - Seats Available: ' . htmlspecialchars($booking['seats_available']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>