<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "d_hms";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userData = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['fetch']) && !empty($_POST['email'])) {
        $email = trim($_POST['email']);
        $sql = "SELECT * FROM careers WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            echo "<script>alert('No any application found from this Email.');</script>";
        }
    }

    if (isset($_POST['delete']) && !empty($_POST['email'])) 
    {
        $email = trim($_POST['email']);
        $delete_sql = "DELETE FROM careers WHERE email = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            echo "<script>alert('Application cancelled successfully.'); window.location.href='User Panel.html';</script>";
        } else {
            echo "<script>alert('Error cancelling application: " . $stmt->error . "');</script>";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Careers Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="HMS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    body {
        margin: 20px;
        overflow-x: hidden;
    }

    .frameset {
        border: 1px solid navy;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .error {
        color: red;
    }

    input,
    select {
        border: 1px solid red;
        border-radius: 3px;
    }
    </style>
    <script>
    function confirmCancellation() {
        if (confirm("Are you sure you want to cancel your application?")) {
            document.getElementById('deleteForm').submit();
        }
    }
    </script>
</head>

<body>
    <div class="head">
        <h1 class="text-center mb-4"><b><u>CHARAK SEVA HOSPITAL</u></b></h1>
        <hr>
    </div>

    <div class="container">
        <h2 class="text-center mb-4"><u>USER CAREERS DATA</u></h2>
        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Enter Your Valid Email ID" required>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small">VIEW DATA</button>
            </div>
        </form>
        <p class="mt-2"><b>Note:</b> Enter your valid email to view your entered details.</p>

        <?php if ($userData): ?>
        <div class="frameset">
            <!-- <hr style="color:red">
            <hr style="color:blue"> -->
            <h2 class="text-center">
                <b>WELCOME DEAR,</b> <span class="text-danger"><?= htmlspecialchars($userData['firstname'])?></span>
                <hr style="color:blue">
                <hr style="color:red">
            </h2><br><br>

            <form id="deleteForm" method="post">
                <input type="hidden" name="delete" value="1">
                <input type="hidden" name="email" value="<?= htmlspecialchars($userData['email']) ?>">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">FIRST NAME</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($userData['firstname']) ?>"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">MIDDLE NAME</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($userData['middlename']) ?>"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">LAST NAME</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($userData['lastname']) ?>"
                            readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">GENDER</label>
                        <select class="form-control" disabled>
                            <option value="MALE" <?= $userData['gender'] == 'MALE' ? 'selected' : '' ?>>MALE</option>
                            <option value="FEMALE" <?= $userData['gender'] == 'FEMALE' ? 'selected' : '' ?>>FEMALE
                            </option>
                            <option value="OTHER" <?= $userData['gender'] == 'OTHER' ? 'selected' : '' ?>>OTHER</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">APPLIED FOR POSITION</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($userData['position']) ?>"
                            readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">APPLICATION STATUS</label>
                    <!-- <input type="text" class="form-control" value="<?= htmlspecialchars($userData['status']) ?>"
                        readonly> -->
                    <input type="text" class="form-control" name="status"
                        value="<?= htmlspecialchars(!empty($userData['status']) ? $userData['status'] : 'PENDING...') ?>"
                        readonly>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger w-100" onclick="confirmCancellation()">CANCEL
                            APPLICATION</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success w-100"
                            onclick="window.location.href='User Panel.html'">WAIT FOR RESPONSE</button>
                    </div>
                </div>

            </form>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>

<!-- 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Careers Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="HMS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    body {
        margin: 20px;
        overflow-x: hidden;
    }

    .frameset {
        border: 1px solid navy;
        padding: 20px;
        margin-bottom: 20px;
    }

    .error {
        color: red;
    }

    input,
    select {
        border: 1px solid red;
        border-radius: 3px;
    }
    </style>
    <script>
    function confirmCancellation() {
        if (confirm("Are you sure you want to cancel your application?")) {
            document.getElementById('deleteForm').submit();
        }
    }
    </script>
</head>

<body>
    <div class="head">
        <h1 class="text-center mb-4"><b><u>CHARAK SEVA HOSPITAL</u></b></h1>
        <hr>
    </div>

    <div class="container">
        <h2 class="text-center mb-4"><u>USER CAREERS DATA</u></h2>
        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Enter Your Valid Email ID" required>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small">VIEW DATA</button>
            </div>
        </form>
        <p><b>Note:</b> Enter your valid email to view your entered details.</p>

        <?php if ($userData): ?>
        <div class="frameset">
            <h2 class="text-center">
                <hr><b>WELCOME DEAR,</b> <span class="text-danger"><?= htmlspecialchars($userData['firstname'])?></span>
                <hr>
            </h2>

            <form id="deleteForm" method="post">
                <input type="hidden" name="email" value="<?= htmlspecialchars($userData['email']) ?>">
                <div class="row-md-6">
                    <label class="form-label">Full Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="firstname"
                            value="<?= htmlspecialchars($userData['firstname']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="middlename"
                            value="<?= htmlspecialchars($userData['middlename']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="lastname"
                            value="<?= htmlspecialchars($userData['lastname']) ?>" readonly required>
                    </div>
                </div>
                <div class="row-md-6">
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control" disabled>
                            <option value="MALE" <?= $userData['gender'] == 'MALE' ? 'selected' : '' ?>>MALE</option>
                            <option value="FEMALE" <?= $userData['gender'] == 'FEMALE' ? 'selected' : '' ?>>FEMALE
                            </option>
                            <option value="OTHER" <?= $userData['gender'] == 'OTHER' ? 'selected' : '' ?>>OTHER</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label">Applied for position</label>
                        <input type="text" class="form-control" id="position" name="position"
                            value="<?= htmlspecialchars($userData['position']) ?>" readonly required>
                    </div>

                    <label for="status" class="form-label">Application status</label>
                    <input type="text" class="form-control" name="status"
                        value="<?= htmlspecialchars($userData['status']) ?>" readonly><br><br>
                    <div class="row-cd-3">
                        <div class="col-md-6">
                            <button type=" button" class="btn btn-success w-100"
                                onclick="window.location.href='User Panel.html'">WAIT FOR RESPONSE</button><br>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger w-100" onclick="confirmCancellation()">CANCEL
                                APPLICATION</button>
                        </div>
                    </div>

            </form>
        </div>
        <?php endif; ?>
    </div>
</body>

</html> -->