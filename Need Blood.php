<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "d_hms";

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST["name"]);
    $bloodGroup = $conn->real_escape_string($_POST["bloodGroup"]);
    $hospital = $conn->real_escape_string($_POST["hospital"]);
    $mobile = $conn->real_escape_string($_POST["mobile"]);

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO blood_requests (patient_name, blood_group, hospital, mobile) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $bloodGroup, $hospital, $mobile);

    if ($stmt->execute()) {
        echo "<script>alert('Blood request submitted successfully!'); window.location.href = 'BloodRequestForm.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
