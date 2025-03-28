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

if (isset($_POST['fetch'])) {
    $mobile = trim($_POST['mobile']);

    $sql = "SELECT * FROM oxygen_requests WHERE mobile = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "<script>alert('No any request found with this mobile number.');</script>";
    }
}

if (isset($_POST['update'])) {
    $mobile = trim($_POST['mobile'] ?? ''); 
    $patient_name = trim($_POST['patient_name'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $mother_name = trim($_POST['mother_name'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $village = trim($_POST['village'] ?? '');
    $post = trim($_POST['post'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $district = trim($_POST['district'] ?? ''); 
    $state = trim($_POST['state'] ?? ''); 
    $admit_date = trim($_POST['admit_date'] ?? '');
    $bed_number = trim($_POST['bed_number'] ?? '');
    $amount_oxygen = trim($_POST['amount_oxygen'] ?? ''); 
    $message = trim($_POST['message'] ?? ''); 

    $update_sql = "UPDATE oxygen_requests SET 
        patient_name=?, father_name=?, mother_name=?, dob=?, gender=?, 
        village=?, post=?, pincode=?, district=?, state=?, admit_date=?, bed_number=?, amount_oxygen=?, message=?
        WHERE mobile=?";

    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssss", 
        $patient_name, $father_name, $mother_name, $dob, $gender, 
        $village, $post, $pincode, $district, $state, $admit_date, 
        $bed_number, $amount_oxygen, $message, $mobile
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
    <title>Oxygen Requests Data</title>
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
        <h2 class="text-center mb-4"><u>OXYGEN REQUEST DATA</u></h2>
        <form method="POST">
            <div class="mb-3">
                <input type="number" class="form-control" name="mobile" maxlength="10" required
                    placeholder="Enter Your Mobile Number, Which You Used During  Request">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small">VIEW DATA</button>
            </div>
        </form><br>

        <p><b>Note:</b> Enter your valid mobile number to view your oxygen request details.</p>

        <?php if ($userData): ?>
        <div class="frameset">
            <h2 class="text-center"><b>WELCOME DEAR,</b>
                <span class="text-danger"><?= htmlspecialchars($userData['patient_name'])?></span>
            </h2>
            <form method="post">
                <div id="patientDetails">
                    <div class="mb-3">
                        <label for="name" class="form-label">Patient's Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= htmlspecialchars($userData['patient_name']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="fathersName" class="form-label">Father's Name</label>
                        <input type="text" class="form-control" id="fathersName" name="father_name"
                            value="<?= htmlspecialchars($userData['father_name']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="mothersName" class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" id="mothersName" name="mother_name"
                            value="<?= htmlspecialchars($userData['mother_name']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob"
                            value="<?= htmlspecialchars($userData['dob']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" readonlyrequired>
                            <option value="" selected disabled>SELECT GENDER</option>
                            <option value="MALE" <?= $userData['gender'] == 'MALE' ? 'selected' : '' ?>>MALE</option>
                            <option value="FEMALE" <?= $userData['gender'] == 'FEMALE' ? 'selected' : '' ?>>FEMALE
                            </option>
                            <option value="OTHER" <?= $userData['gender'] == 'OTHER' ? 'selected' : '' ?>>OTHER</option>


                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="village" class="form-label">Village</label>
                        <input type="text" class="form-control" id="village" name="village"
                            value="<?= htmlspecialchars($userData['village']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="post" class="form-label">Post</label>
                        <input type="text" class="form-control" id="post" name="post"
                            value="<?= htmlspecialchars($userData['post']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">District</label>
                        <input type="text" class="form-control" id="district" name="district"
                            value="<?= htmlspecialchars($userData['district']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state"
                            value="<?= htmlspecialchars($userData['state']) ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="pincode" class="form-label">Pin Code</label>
                        <input type="number" class="form-control" id="pincode" name="pincode" maxlength="6"
                            value="<?= htmlspecialchars($userData['pincode']) ?>" readonly required>
                    </div>
                    <!-- <div class="mb-3">
                    <label for="landmark" class="form-label">Landmark</label>
                    <input type="text" class="form-control" id="landmark" name="landmark" required>-->
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Admit Date</label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?= htmlspecialchars($userData['admit_date']) ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="bed number" class="form-label">Bed Number</label>
                    <input type="number" class="form-control" id="bed number" name="bed_number"
                        value="<?= htmlspecialchars($userData['bed_number']) ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount of Oxygen</label>
                    <input type="select" class="form-control" id="amount" name="amount"
                        value="<?= htmlspecialchars($userData['amount_oxygen']) ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Any Issue</label>
                    <textarea type="text" class="form-control" id="message" name="message" row="3" readonly>
                    <?= htmlspecialchars($userData['message']) ?></textarea>
                </div>
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