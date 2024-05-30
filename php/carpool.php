<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: register.php");
    exit();
}

// Fetch search parameters if available
$search_from = $_SESSION['search_from'] ?? '';
$search_to = $_SESSION['search_to'] ?? '';
$search_date = $_SESSION['search_date'] ?? '';
$start_date = explode(' to ', $search_date)[0] ?? '';
$end_date = explode(' to ', $search_date)[1] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Carpool</title>
    <link rel="stylesheet" href="../css/carpool.css">
</head>
<body>
    <?php include '../html/navbar.html'; ?>
    <main>
        <section class="search">
            <form method="POST" action="carpool_handler.php">
                <div class="form-group">
                    <label for="origin">Origin</label>
                    <select id="origin" name="search_from" required>
                        <option value="" disabled selected>Select Origin Governorate</option>
                        <option value="Tunis">Tunis</option>
                        <option value="Ariana">Ariana</option>
                        <option value="Ben Arous">Ben Arous</option>
                        <option value="Manouba">Manouba</option>
                        <option value="Nabeul">Nabeul</option>
                        <option value="Zaghouan">Zaghouan</option>
                        <option value="Bizerte">Bizerte</option>
                        <option value="Beja">Beja</option>
                        <option value="Jendouba">Jendouba</option>
                        <option value="Le Kef">Le Kef</option>
                        <option value="Siliana">Siliana</option>
                        <option value="Sousse">Sousse</option>
                        <option value="Monastir">Monastir</option>
                        <option value="Mahdia">Mahdia</option>
                        <option value="Sfax">Sfax</option>
                        <option value="Sidi Bouzid">Sidi Bouzid</option>
                        <option value="Kairouan">Kairouan</option>
                        <option value="Kasserine">Kasserine</option>
                        <option value="Gabes">Gabes</option>
                        <option value="Medenine">Medenine</option>
                        <option value="Tataouine">Tataouine</option>
                        <option value="Tozeur">Tozeur</option>
                        <option value="Gafsa">Gafsa</option>
                        <option value="Kebili">Kebili</option>
                        <option value="Djerba">Djerba</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination">Destination</label>
                    <select id="destination" name="search_to" required>
                        <option value="" disabled selected>Select Destination Governorate</option>
                        <option value="Tunis">Tunis</option>
                        <option value="Ariana">Ariana</option>
                        <option value="Ben Arous">Ben Arous</option>
                        <option value="Manouba">Manouba</option>
                        <option value="Nabeul">Nabeul</option>
                        <option value="Zaghouan">Zaghouan</option>
                        <option value="Bizerte">Bizerte</option>
                        <option value="Beja">Beja</option>
                        <option value="Jendouba">Jendouba</option>
                        <option value="Le Kef">Le Kef</option>
                        <option value="Siliana">Siliana</option>
                        <option value="Sousse">Sousse</option>
                        <option value="Monastir">Monastir</option>
                        <option value="Mahdia">Mahdia</option>
                        <option value="Sfax">Sfax</option>
                        <option value="Sidi Bouzid">Sidi Bouzid</option>
                        <option value="Kairouan">Kairouan</option>
                        <option value="Kasserine">Kasserine</option>
                        <option value="Gabes">Gabes</option>
                        <option value="Medenine">Medenine</option>
                        <option value="Tataouine">Tataouine</option>
                        <option value="Tozeur">Tozeur</option>
                        <option value="Gafsa">Gafsa</option>
                        <option value="Kebili">Kebili</option>
                        <option value="Djerba">Djerba</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <button type="submit">Search</button>
            </form>
        </section>
        <section class="rides">
            <?php if (isset($_SESSION['rides']) && count($_SESSION['rides']) > 0): ?>
                <?php foreach ($_SESSION['rides'] as $ride): ?>
                    <div class="ride">
                        <h3><?php echo htmlspecialchars($ride['origin']); ?> to <?php echo htmlspecialchars($ride['destination']); ?></h3>
                        <p>Date: <?php echo htmlspecialchars($ride['departure_time']); ?></p>
                        <p>Driver ID: <?php echo htmlspecialchars($ride['driver_id']); ?></p>
                        <p>Seats Available: <?php echo htmlspecialchars($ride['seats_available']); ?></p>

                        <?php
                        // Fetch the user id
                        $email = $_SESSION['email'];
                        $user_id = null;

                        $sql = "SELECT id FROM users WHERE email = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $stmt->bind_result($user_id);
                        $stmt->fetch();
                        $stmt->close();

                        // Check if the ride is already booked by the user
                        $ride_id = $ride['id'];
                        $sql = "SELECT COUNT(*) FROM bookings WHERE user_id = ? AND ride_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ii", $user_id, $ride_id);
                        $stmt->execute();
                        $stmt->bind_result($booking_count);
                        $stmt->fetch();
                        $stmt->close();
                        
                        // Debugging: Print the values retrieved
                        echo "User ID: $user_id, Ride ID: $ride_id, Booking Count: $booking_count";

                        // Set button class based on booking status
                        $button_class = ($booking_count > 0) ? 'remove-booking' : 'book-ride';
                        $button_text = ($booking_count > 0) ? 'Remove Booking' : 'Book';
                        ?>

                        <form method="POST" action="<?php echo ($booking_count > 0) ? 'remove_booking.php' : 'book_ride.php'; ?>">
                            <input type="hidden" name="ride_id" value="<?php echo htmlspecialchars($ride_id); ?>">
                            <button type="submit" class="<?php echo $button_class; ?>"><?php echo $button_text; ?></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No rides found matching your criteria.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
