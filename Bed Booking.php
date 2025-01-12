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
    $fathersName = $_POST['fathersName'];
    $mothersName = $_POST['mothersName'];
    $dob = $_POST['dob'];
    $village = $_POST['village'];
    $post = $_POST['post'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $landmark = $_POST['landmark'];
    $reason = $_POST['reason'];

    // Prepare SQL query to insert data into the bed_booking table
    $sql = "INSERT INTO bed_booking (mobile, name, father_name, mother_name, dob, village, post, district, state, pincode, landmark, reason)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $mobile, $name, $fathersName, $mothersName, $dob, $village, $post, $district, $state, $pincode, $landmark, $reason);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
