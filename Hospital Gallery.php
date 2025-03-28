<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Image Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 15px;
        /* background-color: #f4f4f4; */
        font-family: Arial, sans-serif;
        background: rgb(0, 195, 255);
        overflow-x: hidden;
        top: 115px;
        left: 0;
        right: 0;
        /* scrollbar-color: red; */
    }

    header {
        width: 100%;
        background: linear-gradient(135deg, rgb(3, 7, 78), rgb(10, 20, 114));
        color: white;
        text-align: center;
        padding: 10px 0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    h2 {
        margin-top: 70px;
        margin-bottom: 5px;
        color: yellow;
        font-size: xx-large;
        font-weight: 700;
    }

    .main {
        width: 100%;
        text-align: center;
        margin-top: 1px;
        /* background: blue; */
        padding: 2px;
        border-radius: 10px;
        /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); */
    }

    .gallery {
        display: grid;
        /* background-color: red; */
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 5px;
        /* padding: 20px; */
    }

    .gallery img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .gallery img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease-in-out;
        z-index: 10000;
    }

    .fullscreen img {
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .fullscreen.active {
        opacity: 1;
        pointer-events: all;
    }

    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 30px;
        cursor: pointer;
        background: rgba(238, 15, 15, 0.7);
        padding: 10px 15px;
        border-radius: 50%;
        transition: 0.3s;
        z-index: 10001;
    }

    .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: red;
    }
    </style>
</head>

<body>
    <header>
        <h1 class="text-center"><b>CHARAK SEVA HOSPITAL</b></h1>
    </header>

    <h2 class="text-center mb-3"><b><u>OUR GALLERY</u></b></h2>
    <div class="main">
        <div class="gallery">
            <?php 
                $host = "localhost";
                $user = "root";
                $pass = "";
                $dbname = "d_hms";
                $conn = new mysqli($host, $user, $pass, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM gallery ORDER BY uploaded_at DESC";
                if ($result = $conn->query($sql)) {
                    while ($row = $result->fetch_assoc()): 
            ?>
            <img src="<?= $row['image_url']; ?>" alt="Gallery Image" onclick="openFullscreen(this)">
            <?php 
                    endwhile; 
                }
                $conn->close();
            ?>
        </div>
    </div>

    <div class="fullscreen" id="fullscreen">
        <span class="close-btn" onclick="closeFullscreen()">&times;</span>
        <img id="fullscreen-img" src="" alt="Full Image">
    </div>

    <script>
    function openFullscreen(img) {
        const fullscreen = document.getElementById("fullscreen");
        const fullscreenImg = document.getElementById("fullscreen-img");
        fullscreenImg.src = img.src;
        fullscreen.classList.add("active");
    }

    function closeFullscreen() {
        document.getElementById("fullscreen").classList.remove("active");
    }
    </script>
</body>

</html>