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

    $sql = "SELECT * FROM appointments WHERE mobile = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "<script>alert('No appointment found with this mobile number.');</script>";
    }
}

if (isset($_POST['update'])) {
    // Fetch and trim input values with fallback
    $firstname = trim($_POST['firstname'] ?? '');
    $middlename = trim($_POST['middlename'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $f_firstname = trim($_POST['f_firstname'] ?? '');
    $f_middlename = trim($_POST['f_middlename'] ?? '');
    $f_lastname = trim($_POST['f_lastname'] ?? '');
    $m_firstname = trim($_POST['m_firstname'] ?? '');
    $m_middlename = trim($_POST['m_middlename'] ?? '');
    $m_lastname = trim($_POST['m_lastname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0; 
    $mobile = trim($_POST['mobile'] ?? ''); 
    $village = trim($_POST['village'] ?? '');
    $post = trim($_POST['post'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $dist = trim($_POST['dist'] ?? ''); 
    $date = trim($_POST['date'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    
    $update_sql = "UPDATE appointments SET 
        firstname=?, middlename=?, lastname=?, f_firstname=?, f_middlename=?, f_lastname=?, 
        m_firstname=?, m_middlename=?, m_lastname=?, gender=?, dob=?, 
        age=?, village=?, post=?, pincode=?, dist=?, date=?, reason=?
        WHERE mobile=?";

    // Prepare statement
    $stmt = $conn->prepare($update_sql);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssisssisss", 
        $firstname, $middlename, $lastname, 
        $f_firstname, $f_middlename, $f_lastname, 
        $m_firstname, $m_middlename, $m_lastname, 
        $gender, $dob, $age, $mobile,
        $village, $post, $pincode, $dist, $date, $reason 
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
    <title>View Appointment Data</title>
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
    <div class="container">
        <h2 class="text-center mb-4"><b><u>CHARAK SEVA HOSPITAL</u></b></h2>
        <form method="POST">
            <div class="mb-3">
                <input type="number" class="form-control" name="mobile" maxlength="10" required
                    placeholder="Enter Your Mobile Number Used During Booking">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small">VIEW DATA</button>
            </div>
        </form><br>

        <p><b>Note:</b> Enter your valid mobile number to view your appointment details.</p>

        <?php if ($userData): ?>
        <div class="frameset">
            <h2 class="text-center"><b>WELCOME DEAR,</b>
                <span
                    class="text-danger"><?= htmlspecialchars($userData['firstname']) . " " . htmlspecialchars($userData['middlename']) . " " . htmlspecialchars($userData['lastname']) ?></span>
            </h2>
            <form method="POST">
                <h6>NAME :</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="firstname" name="firstname"
                            value="<?= htmlspecialchars($userData['firstname']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="middlename" name="middlename"
                            value="<?= htmlspecialchars($userData['middlename']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="lastname" name="lastname"
                            value="<?= htmlspecialchars($userData['lastname']) ?>" readonly required>
                    </div>
                </div>
                <h6>FATHER's NAME :</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="f_firstname" name="f_firstname"
                            value="<?= htmlspecialchars($userData['f_firstname']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="f_middlename" name="f_middlename"
                            value="<?= htmlspecialchars($userData['f_middlename']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="f_lastname" name="f_lastname"
                            value="<?= htmlspecialchars($userData['f_lastname']) ?>" readonly required>
                    </div>
                </div>
                <h6>MOTHER's NAME :</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="m_firstname" name="m_firstname"
                            value="<?= htmlspecialchars($userData['m_firstname']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="m_middlename" name="m_middlename"
                            value="<?= htmlspecialchars($userData['m_middlename']) ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="m_lastname" name="m_lastname"
                            value="<?= htmlspecialchars($userData['m_lastname']) ?>" readonly required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="gender" class="form-label"><b>GENDER</b></label>
                        <select class="form-control" id="gender" name="gender" readonlyrequired>
                            <option value="">SELECT</option>
                            <option value="MALE" <?= $userData['gender'] == 'MALE' ? 'selected' : '' ?>>MALE
                            </option>
                            <option value="FEMALE" <?= $userData['gender'] == 'FEMALE' ? 'selected' : '' ?>>
                                FEMALE
                            </option>
                            <option value="OTHER" <?= $userData['gender'] == 'OTHER' ? 'selected' : '' ?>>
                                OTHER
                            </option>
                        </select>
                        <span class="error" id="genderError"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="dob" class="form-label"><b>DATE OF BIRTH</b></label>
                        <input type="date" class="form-control" id="dob" name="dob"
                            value="<?= htmlspecialchars($userData['dob']) ?>" onchange="calculateAge()" readrequired>
                        <span class="error" id="dobError"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="age" class="form-label"><b>CURRENT AGE</b></label>
                        <input type="text" class="form-control" id="age" name="age"
                            value="<?= htmlspecialchars($userData['age']) ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="mobile" class="form-label"><b>MOBILE NUMBER</b></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" maxlength="10"
                            value="<?= htmlspecialchars($userData['mobile']) ?>" readonlyrequired>
                        <span class="error" id="mobileError"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="village" class="form-label"><b>VILLAGE</b></label>
                        <input type="text" class="form-control" id="village" name="village"
                            value="<?= htmlspecialchars($userData['village']) ?>" readonly required>
                        <span class="error" id="villageError"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="post" class="form-label"><b>POST</b></label>
                        <input type="text" class="form-control" id="post" name="post"
                            value="<?= htmlspecialchars($userData['post']) ?>" readonly required>
                        <span class="error" id="postError"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="pincode" class="form-label"><b>PIN CODE</b></label>
                        <input type="text" class="form-control" id="pincode" maxlength="6" name="pincode"
                            value="<?= htmlspecialchars($userData['pincode']) ?>" readonly required>
                        <span class="error" id="pincodeError"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="district" class="form-label"><b>DISTRICT</b></label>
                        <input type="text" class="form-control" id="dist" name="dist"
                            value="<?= htmlspecialchars($userData['dist']) ?>" readonly required>
                        <span class="error" id="districtError"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="form-label"><b>DATE OF VISIT</b></label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="<?= isset($userData['date']) ? htmlspecialchars($userData['date']) : '' ?>" readonly>
                        <span class="error" id="dateError"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>REASON OF VISIT :</h6>
                    <textarea class="form-control" id="reason" name="reason"
                        rows="3"><?= htmlspecialchars($userData['reason']) ?></textarea>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button type="button" class="btn btn-info" id="editBtn" onclick="enableEdit()"><b>EDIT</b></button>
                    <button type="submit" class="btn btn-success" id="saveBtn" name="update"
                        style="display: none;"><b>SAVE</b></button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    </div>

    <script>
    function enableEdit() {
        document.querySelectorAll("input[readonly]").forEach(input => input.removeAttribute("readonly"));
        document.getElementById("editBtn").style.display = "none";
        document.getElementById("saveBtn").style.display = "inline-block";
    }
    </script>
</body>

</html>