<?php
    // Replace these with your actual database credentials
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
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
    
        // Protect against SQL injection
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
    
        $sql = "SELECT * FROM registration WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
             $row = $result->fetch_assoc();
             $_SESSION['user_id'] = $row['id'];
             $_SESSION['full_name'] = $row['full_name'];
             echo "Login successful!";
             header("Location: User Panel.html");
            // Redirect to a dashboard or home page
        } else {
            echo "<script>alert('Ooo sit!! Invalid Username or Password'); window.location.href = 'User Login.html';</script>";
        }
    }
    
    $conn->close();
?>