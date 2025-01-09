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
        $salutation = $conn->real_escape_string($_POST["salutation"]);
        $firstname = $conn->real_escape_string($_POST["firstname"]);
        $lastname = $conn->real_escape_string($_POST["lastname"]);
        $fatherFirstname = $conn->real_escape_string($_POST["fatherFirstname"]);
        $fatherMiddlename = $conn->real_escape_string($_POST["fatherMiddlename"]);
        $fatherLastname = $conn->real_escape_string($_POST["fatherLastname"]);
        $motherFirstname = $conn->real_escape_string($_POST["motherFirstname"]);
        $motherMiddlename = $conn->real_escape_string($_POST["motherMiddlename"]);
        $motherLastname = $conn->real_escape_string($_POST["motherLastname"]);
        $gender = $conn->real_escape_string($_POST["gender"]);
        $dob = $conn->real_escape_string($_POST["dob"]);
        $mobile = $conn->real_escape_string($_POST["mobile"]);
        $degree = $conn->real_escape_string($_POST["degree"]);
        $joiningDate = $conn->real_escape_string($_POST["joiningDate"]);

                if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile']['tmp_name'];
            $fileName = $_FILES['profile']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadFileDir = 'uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $destPath = $uploadFileDir . uniqid() . '.' . $fileExtension;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $profilePath = $destPath;
                } else {
                    die("Error moving uploaded file.");
                }
            } else {
                die("Unsupported file format.");
            }
        } else {
            $profilePath = null;
        }

        $stmt = $conn->prepare("INSERT INTO doctors (salutation, firstname, lastname, father_firstname, father_middlename, father_lastname, mother_firstname, mother_middlename, mother_lastname, gender, dob, mobile, degree, joining_date, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssss", $salutation, $firstname, $lastname, $fatherFirstname, $fatherMiddlename, $fatherLastname, $motherFirstname, $motherMiddlename, $motherLastname, $gender, $dob, $mobile, $degree, $joiningDate, $profilePath);

        if ($stmt->execute()) {
            echo "Doctor added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
?>