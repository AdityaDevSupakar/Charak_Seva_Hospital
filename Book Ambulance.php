<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Update with your MySQL password
$database = "d_hms";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $_POST['step'];

    if ($step === 'step-1') {
        $mobile_number = $_POST['mobileNumber'];

        // Validate mobile number
        if (preg_match('/^\d{10}$/', $mobile_number)) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number']);
        }
    }

    if ($step === 'step-2') {
        $otp = $_POST['otp'];

        // Hardcoded OTP validation
        if ($otp === '1234') {
            echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        }
    }

    if ($step === 'step-3') {
        $mobile_number = $_POST['mobileNumber'];
        $booker_name = $_POST['bookerName'];
        $location = $_POST['location'];

        // Insert booking details into database
        $sql = "INSERT INTO book_ambulance (mobile_number, booker_name, location) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $mobile_number, $booker_name, $location);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Ambulance booked successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    }
}

$conn->close();
?>