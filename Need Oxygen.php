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
    $mobile = $conn->real_escape_string($_POST["mobile"]);
    $name = $conn->real_escape_string($_POST["name"]);
    $fathersName = $conn->real_escape_string($_POST["fathersName"]);
    $mothersName = $conn->real_escape_string($_POST["mothersName"]);
    $dob = $conn->real_escape_string($_POST["dob"]);
    $village = $conn->real_escape_string($_POST["village"]);
    $post = $conn->real_escape_string($_POST["post"]);
    $district = $conn->real_escape_string($_POST["district"]);
    $state = $conn->real_escape_string($_POST["state"]);
    $pincode = $conn->real_escape_string($_POST["pincode"]);
    $date = $conn->real_escape_string($_POST["date"]);
    $bed_number = $conn->real_escape_string($_POST["bed number"]);
    $amount = $conn->real_escape_string($_POST["amount"]);
    $message = $conn->real_escape_string($_POST["message"]);

    $stmt = $conn->prepare("INSERT INTO oxygen_requests (mobile, name, fathers_name, mothers_name, dob, village, post, district, state, pincode, admit_date, bed_number, amount_oxygen, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss",$mobile,$name,$fathersName,$mothersName,$dob,$village,$post,$district,$state,$pincode,$date,$bed_number,$amount,$message);

    if ($stmt->execute()) {
        echo "Oxygen request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>