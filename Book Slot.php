<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "d_hms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $firstname = $_POST['firstname'] ?? null;
    $middlename = $_POST['middlename'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $f_firstname = $_POST['f_firstname'] ?? null;
    $f_middlename = $_POST['f_middlename'] ?? null;
    $f_lastname = $_POST['f_lastname'] ?? null;
    $m_firstname = $_POST['m_firstname'] ?? null;
    $m_middlename = $_POST['m_middlename'] ?? null;
    $m_lastname = $_POST['m_lastname'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $age = $_POST['age'] ?? null;
    $mobile = $_POST['mobile'] ?? null;
    $village = $_POST['village'] ?? null;
    $post = $_POST['post'] ?? null;
    $pincode = $_POST['pincode'] ?? null;
    $dist = $_POST['dist'] ?? null;
    $reason = $_POST['reason'] ?? null;

    // Validate required fields
    if (
        !$firstname || !$lastname || !$f_firstname || !$f_lastname ||
        !$m_firstname || !$m_lastname || !$gender || !$dob ||
        !$age || !$mobile || !$village || !$post || !$pincode || !$dist || !$reason
    ) {
        die("Please fill in all required fields.");
    }

    // SQL query to insert data
    $sql = "INSERT INTO appointments 
            (firstname, middlename, lastname, f_firstname, f_middlename, f_lastname, m_firstname, m_middlename, m_lastname, gender, dob, age, mobile, village, post, pincode, dist, reason) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssissssss",
        $firstname,
        $middlename,
        $lastname,
        $f_firstname,
        $f_middlename,
        $f_lastname,
        $m_firstname,
        $m_middlename,
        $m_lastname,
        $gender,
        $dob,
        $age,
        $mobile,
        $village,
        $post,
        $pincode,
        $dist,
        $reason
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
