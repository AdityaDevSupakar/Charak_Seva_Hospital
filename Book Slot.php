<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "d_hms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['firstname'];
    $middleName = $_POST['middlename'] ?? null;
    $lastName = $_POST['lastname'];
    $fathersName = $_POST['fathers_name'];
    $mothersName = $_POST['mothers_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $reason = $_POST['reason'];

    // SQL query to insert data
    $sql = "INSERT INTO appointments (firstname, middlename, lastname, fathers_name, mothers_name, gender, dob, age, mobile, address, reason)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssisds", $firstName, $middleName, $lastName, $fathersName, $mothersName, $gender, $dob, $age, $mobile, $address, $reason);

    if ($stmt->execute()) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>