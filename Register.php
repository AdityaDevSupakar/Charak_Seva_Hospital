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
    $errors = [];

    $firstname = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $f_firstname = $_POST['f_firstname'] ?? '';
    $f_middlename = $_POST['f_middlename'] ?? '';
    $f_lastname = $_POST['f_lastname'] ?? '';
    $m_firstname = $_POST['m_firstname'] ?? '';
    $m_middlename = $_POST['m_middlename'] ?? '';
    $m_lastname = $_POST['m_lastname'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $age = $_POST['age'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $altMobile = $_POST['alt-mobile'] ?? '';
    $village = $_POST['village'] ?? '';
    $post = $_POST['post'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $district = $_POST['district'] ?? '';
    $state = $_POST['state'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "Uploads/";
        $fileName = time() . "_" . basename($_FILES['photo']['name']);
        $photoPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $errors[] = "Failed to upload photo.";
        }
    }

    if ($photoPath === null) {
        $errors[] = "Photo is required.";
    }

    // // Validate inputs
    // if (empty($name)) $errors[] = "Name is required.";
    // if (empty($username)) $errors[] = "Username is required.";
    // if (empty($password)) $errors[] = "Password is required.";
    // if (!empty($mobile) && !preg_match('/^\d{10}$/', $mobile)) $errors[] = "Invalid mobile number.";
    // if (!empty($pincode) && !preg_match('/^\d{6}$/', $pincode)) $errors[] = "Invalid pincode.";
    
    if (empty($errors)) {
        $sql = "INSERT INTO registration
            (firstname, middlename, lastname, f_firstname, f_middlename, f_lastname, m_firstname, m_middlename, m_lastname, gender, dob, age, mobile, amobile, village, post, pincode, district, state, username, password, photo_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssssssssssssssssssssss",
                $firstname, $middlename, $lastname, $f_firstname, $f_middlename, $f_lastname, $m_firstname, $m_middlename, $m_lastname, $gender, $dob, $age, $mobile, $altMobile, $village, $post, $pincode, $district, $state, $username, $password, $photoPath
            );

            if ($stmt->execute()) {
                echo "Registration successfully completed.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
