<?php
session_start();
include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $guests = mysqli_real_escape_string($conn, $_POST['guests']);
    $special_request = mysqli_real_escape_string($conn, $_POST['special_request']);

    // Validate date and time
    $current_date = date('Y-m-d');
    $selected_date = date('Y-m-d', strtotime($date));
    
    if ($selected_date < $current_date) {
        echo "<script>alert('Please select a future date for reservation.');</script>";
    } else {
        // Check table availability
        $check_query = "SELECT * FROM reservations WHERE reservation_date = '$date' AND reservation_time = '$time' AND status = 'confirmed'";
        $check_result = mysqli_query($conn, $check_query);
        $existing_reservations = mysqli_num_rows($check_result);

        // Assuming we have 10 tables available
        if ($existing_reservations >= 10) {
            echo "<script>alert('Sorry, no tables available for selected time. Please choose another time slot.');</script>";
        } else {
            // Insert reservation
            $sql = "INSERT INTO reservations (customer_name, email, phone, reservation_date, reservation_time, number_of_guests, special_requests, status) 
                    VALUES ('$name', '$email', '$phone', '$date', '$time', '$guests', '$special_request', 'confirmed')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Reservation successful! We look forward to serving you.');</script>";
            } else {
                echo "<script>alert('Error making reservation. Please try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation - NHP's Restroo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .reservation-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background-color: rgba(54, 63, 63, 0.85);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .reservation-form h2 {
            color: greenyellow;
            text-align: center;
            margin-bottom: 30px;
            font-family: cursive;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: white;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot {
            padding: 8px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 4px;
            cursor: pointer;
        }

        .time-slot:hover {
            background-color: greenyellow;
        }

        .submit-btn {
            background-color: greenyellow;
            color: black;
            font-weight: bold;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: #7cba2b;
        }

        @media screen and (max-width: 480px) {
            .reservation-form {
                padding: 20px;
                margin: 10px;
            }

            .time-slots {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="p_name">
        <img src="symbol.jpg" alt="Restaurant Logo">
        <h1>NHP's Restroo</h1>
    </div>

    <div class="reservation-form">
        <h2>Table Reservation</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="time">Time</label>
                <select id="time" name="time" required>
                    <option value="">Select time</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="11:30">11:30 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="12:30">12:30 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="13:30">1:30 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="18:00">6:00 PM</option>
                    <option value="18:30">6:30 PM</option>
                    <option value="19:00">7:00 PM</option>
                    <option value="19:30">7:30 PM</option>
                    <option value="20:00">8:00 PM</option>
                    <option value="20:30">8:30 PM</option>
                    <option value="21:00">9:00 PM</option>
                </select>
            </div>

            <div class="form-group">
                <label for="guests">Number of Guests</label>
                <select id="guests" name="guests" required>
                    <option value="">Select number of guests</option>
                    <option value="1">1 Person</option>
                    <option value="2">2 People</option>
                    <option value="3">3 People</option>
                    <option value="4">4 People</option>
                    <option value="5">5 People</option>
                    <option value="6">6 People</option>
                    <option value="7">7 People</option>
                    <option value="8">8 People</option>
                </select>
            </div>

            <div class="form-group">
                <label for="special_request">Special Requests (Optional)</label>
                <textarea id="special_request" name="special_request"></textarea>
            </div>

            <button type="submit" class="submit-btn">Make Reservation</button>
        </form>
    </div>

    <script>
        // Disable past dates in the date picker
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);

        // Add client-side validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^\d{10}$/;
            
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
                return;
            }
        });
    </script>
</body>
</html> 