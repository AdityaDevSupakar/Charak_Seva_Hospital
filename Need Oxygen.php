<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "d_hms";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = isset($_POST["mobile"]) ? $conn->real_escape_string($_POST["mobile"]) : '';
    $name = isset($_POST["name"]) ? $conn->real_escape_string($_POST["name"]) : '';
    $fatherName = isset($_POST["father_name"]) ? $conn->real_escape_string($_POST["father_name"]) : '';
    $motherName = isset($_POST["mother_name"]) ? $conn->real_escape_string($_POST["mother_name"]) : '';
    $dob = isset($_POST["dob"]) ? $conn->real_escape_string($_POST["dob"]) : '';
    $village = isset($_POST["village"]) ? $conn->real_escape_string($_POST["village"]) : '';
    $post = isset($_POST["post"]) ? $conn->real_escape_string($_POST["post"]) : '';
    $district = isset($_POST["district"]) ? $conn->real_escape_string($_POST["district"]) : '';
    $state = isset($_POST["state"]) ? $conn->real_escape_string($_POST["state"]) : '';
    $pincode = isset($_POST["pincode"]) ? $conn->real_escape_string($_POST["pincode"]) : '';
    $date = isset($_POST["date"]) ? $conn->real_escape_string($_POST["date"]) : '';
    $bed_number = isset($_POST["bed_number"]) ? $conn->real_escape_string($_POST["bed_number"]) : '';
    $amount = isset($_POST["amount"]) ? $conn->real_escape_string($_POST["amount"]) : '';
    $message = isset($_POST["message"]) ? $conn->real_escape_string($_POST["message"]) : '';

    $stmt = $conn->prepare("INSERT INTO oxygen_requests (mobile, patient_name, father_name, mother_name, dob, village, post, district, state, pincode, admit_date, bed_number, amount_oxygen, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssssss", $mobile, $name, $fatherName, $motherName, $dob, $village, $post, $district, $state, $pincode, $date, $bed_number, $amount, $message);

    if ($stmt->execute()) {
        echo "Oxygen request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
