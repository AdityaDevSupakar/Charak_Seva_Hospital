<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "d_hms"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cv_name = $_FILES['cv']['name'];
        $cv_tmp_name = $_FILES['cv']['tmp_name'];
        $cv_size = $_FILES['cv']['size'];
        $cv_type = $_FILES['cv']['type'];

        $allowed_extensions = ['pdf', 'doc', 'docx'];
        $cv_extension = pathinfo($cv_name, PATHINFO_EXTENSION);

        if (in_array($cv_extension, $allowed_extensions)) {
            $upload_dir = "Uploads/"; 
            $cv_path = $upload_dir . basename($cv_name);

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($cv_tmp_name, $cv_path)) {
                // Prepare SQL query
                $sql = "INSERT INTO careers (name, email, gender, cv) VALUES ('$name', '$email', '$gender', '$cv_path')";

                // Execute the query
                if ($conn->query($sql) === TRUE) {
                    echo "Your record is successfully saved. We will let you know the further processes via call or sms.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Only PDF, DOC, DOCX files are allowed.";
        }
    } else {
        echo "Please upload a valid CV/Resume.";
    }
}

$conn->close();
?>
