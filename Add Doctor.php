<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "d_hms";

// Database connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $salutation = $conn->real_escape_string($_POST["salutation"]);
    $firstname = $conn->real_escape_string($_POST["firstname"]);
    $middlename = $conn->real_escape_string($_POST["middlename"]);
    $lastname = $conn->real_escape_string($_POST["lastname"]);
    $f_firstname = $conn->real_escape_string($_POST["f_firstname"]);
    $f_middlename = $conn->real_escape_string($_POST["f_middlename"]);
    $f_lastname = $conn->real_escape_string($_POST["f_lastname"]);
    $m_firstname = $conn->real_escape_string($_POST["m_firstname"]);
    $m_middlename = $conn->real_escape_string($_POST["m_middlename"]);
    $m_lastname = $conn->real_escape_string($_POST["m_lastname"]);
    $gender = $conn->real_escape_string($_POST["gender"]);
    $dob = $conn->real_escape_string($_POST["dob"]);
    $mobile = $conn->real_escape_string($_POST["mobile"]);
    $degree = $conn->real_escape_string($_POST["degree"]);
    $doctor_type = $conn->real_escape_string($_POST["doctor_type"]);
    $day_from = $conn->real_escape_string($_POST["day_from"]);
    $day_to = $conn->real_escape_string($_POST["day_to"]);
    $time_from = $conn->real_escape_string($_POST["time_from"]);
    $time_to = $conn->real_escape_string($_POST["time_to"]);
    $user_id = $conn->real_escape_string($_POST["user_id"]);
    $password = $conn->real_escape_string($_POST["password"]);

    // Handle file upload
    $profile_pic = null;
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile']['tmp_name'];
        $fileName = $_FILES['profile']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadFileDir = 'Uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $destPath = $uploadFileDir . uniqid() . '.' . $fileExtension;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_pic = $destPath;
            } else {
                die("Error moving uploaded file.");
            }
        } else {
            die("Unsupported file format.");
        }
    } else {
        // Set a default profile picture if no file is uploaded
        $profile_pic = 'Uploads/default_profile.png';
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("
        INSERT INTO doctors 
        (salutation, firstname, middlename, lastname, f_firstname, f_middlename, f_lastname, m_firstname, m_middlename, m_lastname, gender, dob, mobile, degree, day_from, day_to, time_from, time_to, user_id, password, profile_pic, doctor_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssssssssssssssssssss", $salutation, $firstname, $middlename, $lastname, $f_firstname, $f_middlename, $f_lastname, $m_firstname, $m_middlename, $m_lastname, $gender, $dob, $mobile, $degree, $day_from, $day_to, $time_from, $time_to, $user_id, $password, $profile_pic, $doctor_type);

    if ($stmt->execute()) {
        echo "Doctor added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
