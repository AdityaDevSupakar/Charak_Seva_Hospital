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
    } if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["UserName"];
        $password = $_POST["Password"];
    
        // Protect against SQL injection
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
    
        $sql = "SELECT * FROM admin_login WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            echo "Login successful!";
            header("Location: ADMIN_PANEL.html");
            // Redirect to a dashboard or home page
        } else {
            echo("Ooo sit!! Invalid Username or Password");
        }
    }
    
    $conn->close();
?>