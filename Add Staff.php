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
echo "Database connection successful.<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting and sanitizing form data
    $salutation = $conn->real_escape_string($_POST["salutation"]);
    $firstname = $conn->real_escape_string($_POST["firstname"]);
    $middlename = !empty($_POST["middlename"]) ? $conn->real_escape_string($_POST["middlename"]) : null;
    $lastname = $conn->real_escape_string($_POST["lastname"]);
    $f_salutation = $conn->real_escape_string($_POST["f_salutation"]);
    $f_firstname = $conn->real_escape_string($_POST["f_firstname"]);
    $f_middlename = !empty($_POST["f_middlename"]) ? $conn->real_escape_string($_POST["f_middlename"]) : null;
    $f_lastname = $conn->real_escape_string($_POST["f_lastname"]);
    $m_salutation = $conn->real_escape_string($_POST["m_salutation"]);
    $m_firstname = $conn->real_escape_string($_POST["m_firstname"]);
    $m_middlename = !empty($_POST["m_middlename"]) ? $conn->real_escape_string($_POST["m_middlename"]) : null;
    $m_lastname = $conn->real_escape_string($_POST["m_lastname"]);
    $gender = $conn->real_escape_string($_POST["gender"]);
    $dob = $conn->real_escape_string($_POST["dob"]);
    $c_age = intval($_POST["c_age"]);
    $mobile = $conn->real_escape_string($_POST["mobile"]);
    $responsibility = $conn->real_escape_string($_POST["responsibility"]);
    $adhaar = $conn->real_escape_string($_POST["adhaar"]);
    $matric_percentage = isset($_POST["matric"]) ? intval($_POST["matric"]) : null;
    $intermediate_percentage = isset($_POST["inter"]) ? intval($_POST["inter"]) : null;
    $other_degrees = !empty($_POST["other"]) ? $conn->real_escape_string($_POST["other"]) : null;
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);


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
    }

    // Validations
    if (!preg_match('/^\d{10}$/', $mobile)) {
        die("Invalid mobile number. Please enter a 10-digit number.");
    }

    if (!preg_match('/^\d{12}$/', $adhaar)) {
        die("Invalid Aadhaar number. Please enter a 12-digit number.");
    }

    // Insert statement
    $stmt = $conn->prepare(
        "INSERT INTO staffs (
            salutation, firstname, middlename, lastname, f_salutation, f_firstname, f_middlename, f_lastname, 
            m_salutation, m_firstname, m_middlename, m_lastname, gender, dob, c_age, mobile, responsibility, adhaar, 
            matric_percentage, intermediate_percentage, other_degrees, profile_pic, username, password
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Error preparing SQL statement: " . $conn->error);
    }
    echo "Prepared statement created successfully.<br>";

    // Binding parameters
    $stmt->bind_param(
        "ssssssssssssssisssiissss",
        $salutation,
        $firstname,
        $middlename,
        $lastname,
        $f_salutation,
        $f_firstname,
        $f_middlename,
        $f_lastname,
        $m_salutation,
        $m_firstname,
        $m_middlename,
        $m_lastname,
        $gender,
        $dob,
        $c_age,
        $mobile,
        $responsibility,
        $adhaar,
        $matric_percentage,
        $intermediate_percentage,
        $other_degrees,
        $profile_pic,
        $username,
        $password

    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Staff added successfully!'); window.location.href = 'Staffs.php';</script>";
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
