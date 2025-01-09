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
        // Collect and sanitize inputs
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
        $adhaar = $conn->real_escape_string($_POST["adhaar"]);

        // Handle file upload
        $profilePath = null;
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
        }

        // Validate mobile and adhaar
        if (!preg_match('/^\d{10}$/', $mobile)) {
            die("Invalid mobile number. Please enter a 10-digit number.");
        }

        if (!preg_match('/^\d{12}$/', $adhaar)) {
            die("Invalid Adhaar number. Please enter a 12-digit number.");
        }

        // Prepare and bind
        $stmt = $conn->prepare(
            "INSERT INTO staff (salutation, firstname, lastname, father_firstname, father_middlename, father_lastname, mother_firstname, mother_middlename, mother_lastname, gender, dob, mobile, degree, joining_date, adhaar, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssssssssssss",$salutation,$firstname,$lastname,$fatherFirstname,$fatherMiddlename,$fatherLastname,$motherFirstname,$motherMiddlename,$motherLastname,$gender,$dob,$mobile,$degree,$joiningDate,$adhaar,$profilePath);

        if ($stmt->execute()) {
            echo "Staff added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
?>