<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "d_hms";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle image deletion
if (isset($_POST["delete_image"])) {
    $image_id = $_POST["image_id"];
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $image_id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Image deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error deleting image.</div>";
    }
    $stmt->close();
}

// Handle new image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"])) {
    $target_dir = "uploads/";
    foreach ($_FILES["images"]["name"] as $key => $file_name) {
        $target_file = $target_dir . basename($file_name);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowed_types)) {
            $message = "<div class='alert alert-danger'>Only JPG, JPEG, PNG & GIF files are allowed.</div>";
        } elseif ($_FILES["images"]["size"][$key] > 5 * 1024 * 1024) {
            $message = "<div class='alert alert-danger'>File size should not exceed 5MB.</div>";
        } elseif (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO gallery (image_url) VALUES (?)");
            $stmt->bind_param("s", $target_file);
            $stmt->execute();
            $stmt->close();
            $message = "<div class='alert alert-success'>Image uploaded successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
        }
    }
}

// Fetch images from the database
$result = $conn->query("SELECT * FROM gallery");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(to right, rgb(97, 152, 255), rgb(90, 124, 250));
        color: white;
        text-align: center;
        padding-top: 120px;
        overflow-x: hidden;
        overscroll-behavior-y: none;
    }

    .container {
        display: flex;
        gap: 37px;
        justify-content: center;
        flex-wrap: wrap;
        padding: 15px;
    }

    header {
        width: 100%;
        background: linear-gradient(135deg, rgb(4, 8, 73), rgb(49, 70, 255));
        color: white;
        text-align: center;
        padding: 10px 0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .action {
        background: rgba(38, 56, 173, 0.81);
        justify-content: center;
        text-align: left;
        width: 82.5%;
        padding: 20px;
        border-radius: 10px;
        position: fixed;
        top: 67px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        /* margin-bottom: 35px; */
    }

    .box {
        background: rgba(255, 255, 255, 0.15);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(8px);
        width: 45%;
    }

    .box img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin: 5px;
    }

    .preview-box {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-top: 10px;
    }

    .custom-file-upload {
        background: #ff4757;
        padding: 10px 20px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .upload-btn {
        background: #27ae60;
        color: white;
        padding: 10px;
        border-radius: 5px;
        cursor: grab;
        border: none;
    }

    .delete-btn {
        background: #e84118;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <header>
        <h2>CHARAK SEVA HOSPITAL</h2>
    </header>

    <div class="action">
        <label for="action">
            <b>Action :</b>
        </label>
        <?php if (!empty($message)) echo $message; ?>
    </div>

    <div class="container">
        <!-- Modify Existing Images Section -->
        <div class="box">
            <h3>Modify Existing Images</h3>
            <?php if ($result->num_rows > 0): ?>
            <div class="preview-box">
                <?php while ($row = $result->fetch_assoc()): ?>
                <form method="post" style="display:inline-block;" onsubmit="return confirmDelete()">
                    <img src="<?= $row['image_url']; ?>" alt="Image">
                    <input type="hidden" name="image_id" value="<?= $row['id']; ?>">
                    <button type="submit" name="delete_image" class="delete-btn">Delete</button>
                </form>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <p>No images found.</p>
            <?php endif; ?>
        </div>

        <!-- Add New Images Section -->
        <div class="box">
            <h3>Add New Images</h3>


            <form method="post" enctype="multipart/form-data">
                <label for="file-upload" class="custom-file-upload">+</label>
                <input type="file" id="file-upload" name="images[]" multiple accept="image/*" style="display:none;"
                    onchange="previewImages(event)">
                <div class="preview-box" id="preview-box"></div>
                <button type="submit" class="upload-btn">Upload</button>
            </form>
        </div>
    </div>

    <script>
    function previewImages(event) {
        let files = event.target.files;
        let previewBox = document.getElementById("preview-box");
        previewBox.innerHTML = "";
        for (let i = 0; i < files.length; i++) {
            let img = document.createElement("img");
            img.src = URL.createObjectURL(files[i]);
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";
            previewBox.appendChild(img);
        }
    }

    function confirmDelete() {
        return
        confirm("Are you sure you want to delete this image from database? " +
            " Make sure, It cannot be undone!");
    }
    </script>
</body>

</html>