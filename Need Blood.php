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
    
    $firstname = $conn->real_escape_string(trim($_POST["firstname"]));
    $middlename = $conn->real_escape_string(trim($_POST["middlename"]));
    $lastname = $conn->real_escape_string(trim($_POST["lastname"]));
    $gender = $conn->real_escape_string(trim($_POST["gender"]));
    $age = $conn->real_escape_string(trim($_POST["age"]));
    $bloodGroup = $conn->real_escape_string(trim($_POST["bloodGroup"]));
    $mobile = $conn->real_escape_string(trim($_POST["mobile"]));
    $username = $conn->real_escape_string(trim($_POST["username"]));
    $hospital = $conn->real_escape_string(trim($_POST["hospital"]));

    if (!preg_match("/^\d{10}$/", $mobile)) {
        die("Invalid mobile number.");
    }

    $stmt = $conn->prepare("INSERT INTO blood_requests (firstname, middlename, lastname, gender, age, blood_group, mobile, username, hospital) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssissss", $firstname, $middlename, $lastname, $gender, $age, $bloodGroup, $mobile, $username, $hospital);

        if ($stmt->execute()) {
            echo "<script>alert('Blood request submitted successfully!'); window.location.href = 'http://localhost/Charak%20Seva%20Hospital/Blood%20Bank.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
