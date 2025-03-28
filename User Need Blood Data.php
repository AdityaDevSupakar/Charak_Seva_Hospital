<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "d_hms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userData = null;

if (isset($_POST['fetch']) && !empty($_POST['mobile'])) {
    $mobile = trim($_POST['mobile']);

    $sql = "SELECT * FROM blood_requests WHERE mobile = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "<script>alert('No any request found from this mobile number.');</script>";
    }
}

if (isset($_POST['update']) && isset($userData)) {
    $first_name = trim($_POST['firstname'] ?? '');
    $middle_name = trim($_POST['middlename'] ?? '');
    $last_name = trim($_POST['lastname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? '');
    

    $update_sql = "UPDATE blood_requests SET 
        first_name=?, middle_name=?, last_name=?, gender=?, 
        age=?, blood_group=?";
        

    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssssss", 
        $firstname, $middlename, $lastname, $gender, 
        $age, $blood_group, 
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating data: " . $stmt->error . "');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Request Data</title>
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

    input {
        border: 1px solid red;
        border-radius: 3px;
    }
    </style>
</head>

<body>
    <div class="head">
        <h1 class="text-center mb-4"><b><u>CHARAK SEVA HOSPITAL</u></b>
            <hr>
        </h1>
    </div><br>
    <div class="container">
        <h2 class="text-center mb-4"><u>BLOOD REQUEST DATA</u></h2>
        <form method="POST">
            <div class="mb-3">
                <input type="number" class="form-control" name="mobile" maxlength="10" required
                    placeholder="Enter Your Mobile Number, Which You Enter During  Request">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small">VIEW DATA</button>
            </div>
        </form><br>

        <p><b>Note:</b> Enter your valid mobile number to view your blood request details.</p><br><br>

        <?php if ($userData): ?>
        <div class="frameset">
            <h2 class="text-center">
                <hr><b>WELCOME DEAR,</b>
                <span
                    class="text-danger"><?= htmlspecialchars($userData['firstname']).''.htmlspecialchars($userData['middlename']).' '.htmlspecialchars($userData['lastname'])?></span>
                <hr>
            </h2><br>

            <form id="detailsForm" method="post" action="Need Blood.php">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="mobile" class="form-label"><b>Mobile Number</b><span
                                class="text-danger">*</span></label>
                        <input value="<?= isset($userData['mobile']) ? htmlspecialchars($userData['mobile']) : '' ?>"
                            disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label"><b>Username</b><span
                                class="text-danger">*</span></label>
                        <input
                            value="<?= isset($userData['username']) ? htmlspecialchars($userData['username']) : '' ?>"
                            disabled>
                    </div>

                </div>

                <label class="form-label">Patient's Name<span class="text-danger">*</span></label>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="firstname" name="firstname"
                            value="<?= htmlspecialchars($userData['firstname']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="middlename" name="middlename"
                            value="<?= htmlspecialchars($userData['middlename']) ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="lastname" name="lastname"
                            value="<?= htmlspecialchars($userData['lastname']) ?>" readonly required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender<span class="text-danger">*</span></label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="MALE" <?= $userData['gender'] == 'MALE' ? 'selected' : '' ?>>MALE
                        </option>
                        <option value="FEMALE" <?= $userData['gender'] == 'FEMALE' ? 'selected' : '' ?>>FEMALE
                        </option>
                        <option value="OTHER" <?= $userData['gender'] == 'OTHER' ? 'selected' : '' ?>>OTHER
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="age" name="age"
                        value="<?= htmlspecialchars($userData['age']) ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="bloodGroup" class="form-label">Blood Group<span class="text-danger">*</span></label>
                    <select class="form-control" id="bloodGroup" name="bloodGroup" required>
                        <option value="" disabled selected>Select Blood Group</option>
                        <option value="A+" <?= $userData['blood_group'] == 'A+' ? 'selected' : '' ?>>A+</option>
                        <option value="A-" <?= $userData['blood_group'] == 'A-' ? 'selected' : '' ?>>A-</option>
                        <option value="B+" <?= $userData['blood_group'] == 'B+' ? 'selected' : '' ?>>B+</option>
                        <option value="B-" <?= $userData['blood_group'] == 'B-' ? 'selected' : '' ?>>B-</option>
                        <option value="AB+" <?= $userData['blood_group'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                        <option value="AB-" <?= $userData['blood_group'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                        <option value="O+" <?= $userData['blood_group'] == 'O+' ? 'selected' : '' ?>>O+</option>
                        <option value="O-" <?= $userData['blood_group'] == 'O-' ? 'selected' : '' ?>>O-</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="hospital" class="form-label">Hospital</label>
                    <select class="form-control" id="hospital" name="hospital" required>
                        <option value="CHARAK SEVA HOSPITAL"
                            <?= $userData['hospital'] == 'CHARAK SEVA HOSPITAL' ? 'selected' : '' ?>>CHARAK
                            SEVA HOSPITAL
                        </option>
                    </select>
                </div>
                <button type="button" class="btn btn-info w-100" id="editBtn" onclick="enableEdit()">Edit</button>
                <button type="submit" class="btn btn-success w-100" id="saveBtn" name="update"
                    style="display: none;">Save</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    </div>

    <script>
    function enableEdit() {
        document.querySelectorAll("input[readonly], textarea[readonly], select[readonly]").forEach(input => {
            input.removeAttribute("readonly");
        });

        document.getElementById("editBtn").style.display = "none";
        document.getElementById("saveBtn").style.display = "inline-block";
    }
    </script>
</body>

</html>