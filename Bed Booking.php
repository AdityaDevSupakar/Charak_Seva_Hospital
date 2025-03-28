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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $mobile = $_POST['mobile'];
    $name = $_POST['name'];
    $fathersName = $_POST['father_name'];
    $mothersName = $_POST['mother_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $village = $_POST['village'];
    $post = $_POST['post'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $landmark = $_POST['landmark'];
    $reason = $_POST['reason'];

    // Validate inputs
    if (!preg_match('/^\d{10}$/', $mobile)) {
        die("<script>alert('Invalid mobile number. Please enter a 10-digit number.');</script>");
    }
    if (!preg_match('/^\d{6}$/', $pincode)) {
        die("<script>alert('Invalid pincode. Please enter a 6-digit pincode.');</script>");
    }

    // Prepare SQL query to insert data into the bed_booking table
    $sql = "INSERT INTO bed_booking (mobile, name, father_name, mother_name, dob, gender, village, post, district, state, pincode, landmark, reason) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $mobile, $name, $fathersName, $mothersName, $dob, $gender, $village, $post, $district, $state, $pincode, $landmark, $reason);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href='http://localhost/Charak%20Seva%20Hospital/index.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>