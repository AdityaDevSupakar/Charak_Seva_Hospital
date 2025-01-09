<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $category = htmlspecialchars($_POST['category']);
    $cv = $_FILES['cv'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($category)) {
        die("All fields are required!");
    }

    // Validate file upload
    $allowedExtensions = ['pdf', 'doc', 'docx'];
    $fileExtension = strtolower(pathinfo($cv['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Invalid file type. Only PDF, DOC, and DOCX files are allowed.");
    }

    if ($cv['size'] > 2 * 1024 * 1024) { // 2 MB limit
        die("File size exceeds the limit of 2 MB.");
    }

    // Move uploaded file
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFilePath = $uploadDir . basename($cv['name']);
    if (!move_uploaded_file($cv['tmp_name'], $uploadFilePath)) {
        die("Failed to upload the file. Please try again.");
    }

    // Optionally save to a database (MySQL example)
    
    $conn = new mysqli("localhost", "username", "password", "database_name");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO careers (name, email, category, resume_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $category, $uploadFilePath);

    if ($stmt->execute()) {
        echo "Your application has been submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>