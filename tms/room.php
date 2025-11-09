<?php
// Database connection (same as before)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$full_names = $phone_number = $id_number = $room_number = $date_of_birth = $age = "";
$room_price = 0;
$success_message = $error_message = "";

// Room prices array 
$room_prices = array();
for ($i = 1; $i <= 30; $i++) {
    if ($i <= 5) {
        $room_prices[$i] = 1000 + ($i - 1) * 500;
    } elseif ($i <= 20) {
        $room_prices[$i] = 3000 + ($i - 5) * 500;
    } elseif ($i <= 29) {
        $room_prices[$i] = 10000 + ($i - 20) * 500;
    } else {
        $room_prices[$i] =
        
        
         30000;
    }
}

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $full_names = htmlspecialchars(trim($_POST["full_names"]));
    $phone_number = htmlspecialchars(trim($_POST["phone_number"]));
    $id_number = htmlspecialchars(trim($_POST["id_number"]));
    $room_number = intval($_POST["room_number"]);
    $date_of_birth = $_POST["date_of_birth"];
    
    // Calculate age
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;
    
    // Get room price
    $room_price = $room_prices[$room_number];
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO rooms (full_names, phone_number, id_number, room_number, room_price, date_of_birth, age) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssidsi", $full_names, $phone_number, $id_number, $room_number, $room_price, $date_of_birth, $age);
    
    if ($stmt->execute()) {
        $success_message = "Room booked successfully!";
        // Clear form
        $full_names = $phone_number = $id_number = $room_number = $date_of_birth = $age = "";
        $room_price = 0;
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS - Room Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --error-color: #ff3333;
            --border-radius: 12px;
            --box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            position: relative;
        }

        .header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .form-container {
            padding: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 1em;
        }

        .price-display {
            padding: 12px 15px;
            background-color: #e9f7ef;
            border-radius: var(--border-radius);
            margin-top: 5px;
            font-weight: 600;
            color: var(--success-color);
            border: 2px solid #d1fae5;
            text-align: center;
            font-size: 1.1rem;
        }

        .btn {
            grid-column: 1 / -1;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: linear-gradient(to right, var(--secondary-color), var(--primary-color));
        }

        .message {
            padding: 15px;
            margin: 0 30px 20px;
            border-radius: var(--border-radius);
            text-align: center;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
        }

        .success {
            background-color: #e6fffa;
            color: var(--success-color);
            border: 2px solid #b7eb8f;
        }

        .error {
            background-color: #fff1f0;
            color: var(--error-color);
            border: 2px solid #ffccc7;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .form-section h2 {
            color: var(--secondary-color);
            font-size: 1.4rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        @media (max-width: 768px) {
            .form-container {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
        }

        /* Floating animation for visual interest */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .container {
            animation: float 6s ease-in-out infinite;
        }

        /* Pulse animation for price display */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .price-display {
            animation: pulse 2s infinite;
        }

        /* Glow effect for focus */
        .form-control:focus {
            animation: glow 1.5s infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 5px rgba(67, 97, 238, 0.5);
            }
            to {
                box-shadow: 0 0 15px rgba(67, 97, 238, 0.8);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Blue Post Management System</h1>
            <p>Book your perfect room with ease</p>
        </div>
        
        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-section">
                    <h2>Personal Information</h2>
                    
                    <div class="form-group">
                        <label for="full_names">Full Names</label>
                        <input type="text" class="form-control" id="full_names" name="full_names" value="<?php echo $full_names; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_number">ID Number</label>
                        <input type="text" class="form-control" id="id_number" name="id_number" value="<?php echo $id_number; ?>" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Room Details</h2>
                    
                    <div class="form-group">
                        <label for="room_number">Room Number</label>
                        <select class="form-control" id="room_number" name="room_number" required onchange="updateRoomPrice()">
                            <option value="">Select a room</option>
                            <?php for ($i = 1; $i <= 30; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($room_number == $i) ? 'selected' : ''; ?>>
                                    Room <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Room Price</label>
                        <div id="priceDisplay" class="price-display">
                            <?php echo ($room_price > 0) ? 'KES ' . number_format($room_price, 2) : 'Select a room to see price'; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>" required onchange="calculateAge()">
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" class="form-control" id="age" name="age" value="<?php echo $age; ?>" readonly>
                    </div>
                </div>
                <a href="mpesa.php">PAY FIRST</a><br><br>
                <button type="submit" class="btn">Book Room Now</button>
            </form>
        </div>
    </div>

    <script>
        // Room prices array matching the PHP array
        const roomPrices = {
            <?php 
            foreach ($room_prices as $room => $price) {
                echo "$room: $price,\n";
            }
            ?>
        };

        function updateRoomPrice() {
            const roomNumber = document.getElementById('room_number').value;
            const priceDisplay = document.getElementById('priceDisplay');
            
            if (roomNumber && roomPrices[roomNumber]) {
                priceDisplay.textContent = 'KES ' + roomPrices[roomNumber].toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                priceDisplay.style.animation = 'none';
                setTimeout(() => {
                    priceDisplay.style.animation = 'pulse 2s infinite';
                }, 10);
            } else {
                priceDisplay.textContent = 'Select a room to see price';
            }
        }

        function calculateAge() {
            const dobInput = document.getElementById('date_of_birth');
            const ageInput = document.getElementById('age');
            
            if (dobInput.value) {
                const dob = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                ageInput.value = age;
            } else {
                ageInput.value = '';
            }
        }

        // Initialize room price if room is already selected
        document.addEventListener('DOMContentLoaded', function() {
            updateRoomPrice();
            
            // Add animation to form elements on load
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateY(20px)';
                group.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>