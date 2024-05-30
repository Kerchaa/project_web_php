<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Itinerary - RASKI Carpool Service</title>
    <link rel="stylesheet" href="../css/registerlog.css">
</head>
<body>
    <?php include '../html/navbar.html'; ?>

    <div class="container">
        <h2>Add New Itinerary</h2>
        <?php
        // Check if there are any errors stored in session
        if (isset($_SESSION['add_errors'])) {
            $errors = $_SESSION['add_errors'];
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
            unset($_SESSION['add_errors']);
        }
        ?>
    <form action="add_handler.php" method="post">
        <div class="form-group">
            <label for="origin">Origin</label>
            <select id="origin" name="origin" required>
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
            </select>
        </div>
        <div class="form-group">
            <label for="destination">Destination</label>
            <select id="destination" name="destination" required>
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
            </select>
        </div>
        <div class="form-group">
            <label for="departure_time">Departure Time</label>
            <input type="datetime-local" id="departure_time" name="departure_time" required>
        </div>
        <div class="form-group">
            <label for="seats_available">Seats Available</label>
            <select id="seats_available" name="seats_available" required>
                <option value="" disabled selected>Select Seats Available</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <button type="submit">Add Itinerary</button>
    </form>

