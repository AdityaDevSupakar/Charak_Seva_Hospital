<?php
$servername = "localhost"; 
$username = "root";        
$password = "";            
$database = "d_hms";       

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    var_dump($_POST); 

    $bookername = htmlspecialchars(trim($_POST['bookername']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $location = htmlspecialchars(trim($_POST['location']));
    $landmark = htmlspecialchars(trim($_POST['landmark']));

    if (empty($bookername) || empty($mobile) || empty($location) || empty($landmark)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO book_ambulance (booker_name, mobile, location, landmark) VALUES (?, ?, ?, ?)");
        
        if (!$stmt) {
            error_log("Failed to prepare statement: " . $conn->error);
            die("Failed to prepare statement");
        }

        $stmt->bind_param("ssss", $bookername, $mobile, $location, $landmark);

        
        if ($stmt->execute()) {
            echo "<script>alert('Ambulance booked successfully!');</script>";
            echo "<script>window.location.href = 'index.html';</script>";
        } else {
            error_log("Failed to execute query: " . $stmt->error);
            echo "<script>alert('Failed to book ambulance. Please try again later.');</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
