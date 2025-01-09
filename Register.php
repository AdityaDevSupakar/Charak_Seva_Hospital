<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "d_hms";

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Input data with fallback values
    $name = $_POST['name'] ?? '';
    $fatherName = $_POST['father-name'] ?? '';
    $motherName = $_POST['mother-name'] ?? '';
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
    $photo = $_FILES['photo'] ?? null;

    // Validate inputs
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if (!empty($mobile) && !preg_match('/^\d{10}$/', $mobile)) $errors[] = "Invalid mobile number.";
    if (!empty($pincode) && !preg_match('/^\d{6}$/', $pincode)) $errors[] = "Invalid pincode.";

    // Process photo
    $photoPath = null;
    if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "Photos/";
        $photoPath = $uploadDir . basename($photo['name']);
        if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
            $errors[] = "Failed to upload photo.";
        }
    }

    // If no errors, proceed with the database operation
    if (empty($errors)) {
        $sql = "INSERT INTO registration
            (name, fathername, mothername, gender, dob, age, mobile, amobile, village, post, pincode, district, state, username, password, photo_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssssssssssssssss",
                $name, $fatherName, $motherName, $gender, $dob, $age, $mobile, $altMobile, $village, $post, $pincode, $district, $state, $username, $password, $photoPath
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
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>