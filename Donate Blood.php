<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Update if your MySQL password is set
$database = "d_hms";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $father_name = $_POST['father_name'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];

    // Prepare the SQL query to insert data
    $sql = "INSERT INTO donate_blood (name, father_name, dob, blood_group, address, mobile)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $father_name, $dob, $blood_group, $address, $mobile);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for registering! Your data has been saved successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
