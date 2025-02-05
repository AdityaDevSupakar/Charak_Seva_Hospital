<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''));
    $middlename = htmlspecialchars(trim($_POST['middlename'] ?? ''));
    $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''));    
    $gender = htmlspecialchars(trim($_POST['gender'] ?? ''));    
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $problem = htmlspecialchars(trim($_POST['problem'] ?? ''));

    if (empty($firstname) || empty($middlename) || empty($lastname) || empty($gender)  || empty($email) || empty($subject) || empty($problem)) {
        echo "All fields are required. Please fill all the fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // // Send email
    // $to = "charakseva.healthcare@gmail.com";
    // $headers = "From: $email\r\n";
    // $headers .= "Reply-To: $email\r\n";
    // $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    // $fullMessage = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$problem";

    // if (mail($to, $subject, $fullMessage, $headers)) {
    //     echo "Thank you, $name! Your message has been sent successfully.<br>";
    // } else {
    //     echo "Error: Unable to send your message. Please try again later.<br>";
    // }

    $servername = 'localhost';
    $username = 'root'; 
    $password = '';
    $dbname = 'd_hms';   

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO contact_us (firstname, middlename, lastname, gender, email, subject, problem) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Statement preparation failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $gender, $email, $subject, $problem);

    if ($stmt->execute()) {
        echo "Your message has been recorded.";
    } else {
        echo "Error saving your data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>