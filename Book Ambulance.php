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
    
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $middlename = htmlspecialchars(trim($_POST['middlename']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $age = htmlspecialchars(trim($_POST['age']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $place = htmlspecialchars(trim($_POST['place']));
    $landmark = htmlspecialchars(trim($_POST['landmark']));
    $area = htmlspecialchars(trim($_POST['area']));
    $street = htmlspecialchars(trim($_POST['street']));
    
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($age) || empty($mobile) || empty($place) || empty($landmark) || empty($area) || empty($street)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO book_ambulance (firstname, middlename, lastname, gender, age, mobile, place, landmark, area, street) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            error_log("Failed to prepare statement: " . $conn->error);
            die("Failed to prepare statement");
        }

        $stmt->bind_param("ssssisssss", $firstname, $middlename, $lastname, $gender, $age, $mobile, $place, $landmark, $area, $street);

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
