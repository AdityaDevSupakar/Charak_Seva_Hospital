<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "d_hms";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $gender = trim($_POST['gender']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $position = trim($_POST['position']);

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($email) || empty($position)) {
        die("All required fields must be filled.");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // CV Upload Handling
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
        $cv_name = $_FILES['cv']['name'];
        $cv_tmp_name = $_FILES['cv']['tmp_name'];
        $cv_size = $_FILES['cv']['size'];
        $cv_type = $_FILES['cv']['type'];

        // Allowed extensions and file size limit (5MB)
        $allowed_extensions = ['pdf', 'doc', 'docx'];
        $cv_extension = strtolower(pathinfo($cv_name, PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($cv_extension, $allowed_extensions)) {
            die("Only PDF, DOC, DOCX files are allowed.");
        }

        if ($cv_size > $max_size) {
            die("File size should not exceed 5MB.");
        }

        // Unique file name to prevent overwriting
        $upload_dir = "Uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if not exists
        }

        $new_filename = uniqid("CV_", true) . "." . $cv_extension;
        $cv_path = $upload_dir . $new_filename;

        // Move file
        if (move_uploaded_file($cv_tmp_name, $cv_path)) {
            // Use prepared statement to prevent SQL injection
            $sql = "INSERT INTO careers (firstname, middlename, lastname, gender, email, position, cv) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $gender, $email, $position, $cv_path);

            if ($stmt->execute()) {
                echo "Your record is successfully saved. We will contact you via call or SMS.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Please upload a valid CV/Resume.";
    }
}

$conn->close();
?>
