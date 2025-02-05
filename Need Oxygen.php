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
    $mobile = trim($_POST["mobile"] ?? '');
    $name = trim($_POST["name"] ?? '');
    $fatherName = trim($_POST["father_name"] ?? '');
    $motherName = trim($_POST["mother_name"] ?? '');
    $dob = trim($_POST["dob"] ?? '');
    $gender = trim($_POST["gender"] ?? '');
    $village = trim($_POST["village"] ?? '');
    $post = trim($_POST["post"] ?? '');
    $district = trim($_POST["district"] ?? '');
    $state = trim($_POST["state"] ?? '');
    $pincode = trim($_POST["pincode"] ?? '');
    $date = trim($_POST["date"] ?? '');
    $bed_number = trim($_POST["bed_number"] ?? '');
    $amount = trim($_POST["amount"] ?? '');
    $message = trim($_POST["message"] ?? '');

    // Basic validation
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        die("Invalid mobile number.");
    }
    if (!preg_match("/^[0-9]{6}$/", $pincode)) {
        die("Invalid pin code.");
    }
    if (empty($name) || empty($fatherName) || empty($motherName) || empty($dob) || empty($gender) || empty($village) || empty($post) || empty($district) || empty($state) || empty($pincode) || empty($date) || empty($bed_number) || empty($amount) || empty($message)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("INSERT INTO oxygen_requests (mobile, patient_name, father_name, mother_name, dob, gender, village, post, district, state, pincode, admit_date, bed_number, amount_oxygen, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssss", $mobile, $name, $fatherName, $motherName, $dob, $gender, $village, $post, $district, $state, $pincode, $date, $bed_number, $amount, $message);

    if ($stmt->execute()) {
        echo "Oxygen request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
