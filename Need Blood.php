<?php
// Database connection settings
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "d_hms";

// Establish connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = $conn->real_escape_string(trim($_POST["name"]));
    $bloodGroup = $conn->real_escape_string(trim($_POST["bloodGroup"]));
    $mobile = $conn->real_escape_string(trim($_POST["mobile"]));
    $hospital = $conn->real_escape_string(trim($_POST["hospital"]));

    // Validate inputs
    if (!preg_match("/^\d{10}$/", $mobile)) {
        die("Invalid mobile number.");
    }

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO blood_requests (patient_name, blood_group, hospital, mobile) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $bloodGroup, $hospital, $mobile);
        
        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Blood request submitted successfully!'); window.location.href = 'BloodRequestForm.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>
