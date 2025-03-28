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
    if (isset($_POST['fetch']) && !empty($_POST['mobile'])) {
        $mobile = trim($_POST['mobile']);

        $sql = "SELECT * FROM bed_booking WHERE mobile = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            echo "<script>alert('No bed request found for this mobile number.');</script>";
        }
    }

    if (isset($_POST['update']) && !empty($_POST['mobile'])) {
        $mobile = trim($_POST['mobile']); // Make sure we have mobile to update the record
        $name = trim($_POST['name'] ?? '');
        $father_name = trim($_POST['father_name'] ?? '');
        $mother_name = trim($_POST['mother_name'] ?? '');
        $dob = trim($_POST['dob'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $village = trim($_POST['village'] ?? '');
        $post = trim($_POST['post'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $pincode = trim($_POST['pincode'] ?? '');
        $landmark = trim($_POST['landmark'] ?? '');
        $reason = trim($_POST['reason'] ?? '');

        $update_sql = "UPDATE bed_booking SET 
            name=?, father_name=?, mother_name=?, dob=?, gender=?, village=?, post=?, district=?, 
            state=?, pincode=?, landmark=?, reason=? WHERE mobile=?";

        $stmt = $conn->prepare($update_sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssss",
            $name, $father_name, $mother_name, $dob, $gender,
            $village, $post, $district, $state, $pincode, $landmark, $reason, $mobile
        );

        if ($stmt->execute()) {
            echo "<script>alert('Data updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating data: " . $stmt->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Booking Request Data</title>
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
</head>

<body>
    <div class="head">
        <h1 class="text-center mb-4"><b><u>CHARAK SEVA HOSPITAL</u></b></h1>
        <hr>
    </div>

    <div class="container">
        <h2 class="text-center mb-4"><u>BED BOOKING DATA</u></h2>
        <form method="POST">
            <div class="mb-3">
                <input type="number" class="form-control" name="mobile" maxlength="10" required
                    placeholder="Enter Your Mobile Number">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small">VIEW DATA</button>
            </div>
        </form>
        <p><b>Note:</b> Enter your valid mobile number to view your bed booking details.</p>

        <?php if ($userData): ?>
        <div class="frameset">
            <h2 class="text-center">
                <hr><b>WELCOME DEAR,</b> <span class="text-danger"><?= htmlspecialchars($userData['name'])?></span>
                <hr>
            </h2>

            <form id="detailsForm" method="post">
                <input type="hidden" name="mobile" value="<?= htmlspecialchars($userData['mobile']) ?>">

                <label class="form-label">Patient's Name</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($userData['name']) ?>"
                    readonly required>

                <label class="form-label">Father's Name</label>
                <input type="text" class="form-control" name="father_name"
                    value="<?= htmlspecialchars($userData['father_name']) ?>" readonly required>

                <label class="form-label">Mother's Name</label>
                <input type="text" class="form-control" name="mother_name"
                    value="<?= htmlspecialchars($userData['mother_name']) ?>" readonly required>

                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($userData['dob']) ?>"
                    readonly required>

                <label class="form-label">Gender</label>
                <select name="gender" class="form-control" disabled>
                    <option value="MALE" <?= $userData['gender'] == 'MALE' ? 'selected' : '' ?>>MALE</option>
                    <option value="FEMALE" <?= $userData['gender'] == 'FEMALE' ? 'selected' : '' ?>>FEMALE</option>
                    <option value="OTHER" <?= $userData['gender'] == 'OTHER' ? 'selected' : '' ?>>OTHER</option>
                </select>

                <label for="village" class="form-label">Village<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="village" name="village"
                    value="<?= htmlspecialchars($userData['village']) ?>" readonly required>

                <label for="landmark" class="form-label">Landmark<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="landmark" name="landmark"
                    value="<?= htmlspecialchars($userData['landmark']) ?>" readonly required>

                <label for="post" class="form-label">Post<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="post" name="post"
                    value="<?= htmlspecialchars($userData['post']) ?>" readonly required>

                <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="pincode" name="pincode"
                    value="<?= htmlspecialchars($userData['pincode']) ?>" readonly required>

                <label for="district" class="form-label">District<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="district" name="district"
                    value="<?= htmlspecialchars($userData['district']) ?>" readonly required>

                <label for="state" class="form-label">State<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="state" name="state"
                    value="<?= htmlspecialchars($userData['state']) ?>" readonly required>


                <label class="form-label">Reason</label>
                <input type="text" class="form-control" name="reason"
                    value="<?= htmlspecialchars($userData['reason']) ?>" readonly required>

                <button type="button" class="btn btn-info w-100" id="editBtn" onclick="enableEdit()">Edit</button>
                <button type="submit" class="btn btn-success w-100" name="update" id="saveBtn"
                    style="display: none;">Save</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <script>
    function enableEdit() {
        document.querySelectorAll("input[readonly], select[disabled]").forEach(input => {
            input.removeAttribute("readonly");
            input.removeAttribute("disabled");
        });
        document.getElementById("editBtn").style.display = "none";
        document.getElementById("saveBtn").style.display = "block";
    }
    </script>
</body>

</html>