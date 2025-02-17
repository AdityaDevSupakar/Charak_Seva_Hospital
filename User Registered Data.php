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
    $userid = trim($_POST['userid']);
    
    $sql = "SELECT * FROM registration WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "<script>alert('User ID not found');</script>";
    }
}

if (isset($_POST['update'])) {
  
   $firstname = $_POST['firstname'];
   $middlename = $_POST['middlename'];
   $lastname = $_POST['lastname'];
   $f_firstname = $_POST['f_firstname'];
   $_fmiddlename = $_POST['f_middlename'];
   $f_lastname = $_POST['f_lastname'];
   $m_firstname = $_POST['m_firstname'];
   $m_middlename = $_POST['m_middlename'];
   $m_lastname = $_POST['m_lastname'];
   $gender = $_POST['gender'];
   $dob = $_POST['dob'];
   $age = $_POST['age'];
   $mobile = $_POST['mobile'];
   $alt_mobile = $_POST['alt-mobile'];
   $village = $_POST['village'];
   $post = $_POST['post'];
   $pincode = $_POST['pincode'];
   $district = $_POST['district'];
   $state = $_POST['state'];
   $userid = $_POST['username'];
   $password = $_POST['password'];
//    $email = $_POST['email']; // Added missing variable
  

$update_sql = "UPDATE registration SET 
    firstname=?, middlename=?, lastname=?, f_firstname=?, f_middlename=?, f_lastname=?, 
    m_firstname=?, m_middlename=?, m_lastname=?, gender=?, dob=?, 
    age=?, mobile=?, amobile=?, village=?, post=?, pincode=?, district=?, state=?, password=? 
    WHERE username=?";

$stmt = $conn->prepare($update_sql);
$stmt->bind_param("sssssssssssssssssssss", 
    $firstname, $middlename, $lastname, $f_firstname, $f_middlename, $f_lastname, 
    $m_firstname, $m_middlename, $m_lastname, $gender, $dob, 
    $age, $mobile, $alt_mobile, $village, $post, $pincode, $district, $state, $password, $userid);

if ($stmt->execute()) {
    echo "<script>alert('Data updated successfully');</script>";
} else {
    echo "<script>alert('Error updating data');</script>";
}
 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registered Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="HMS.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    .age-label {
        display: inline-block;
        margin-left: 10px;
    }

    .error {
        color: red;
    }

    input {
        border: 1px solid red;
        border-radius: 3px;
    }
    </style>
    <!-- <script>
    function enableEdit() {
        document.querySelectorAll("input[readonly], select[disabled]").forEach(input => input.removeAttribute(
            "readonly"));
        document.querySelectorAll("select").forEach(select => select.removeAttribute("disabled"));
        document.getElementById("editBtn").style.display = "none";
        document.getElementById("saveBtn").style.display = "inline-block";
    }
    </script> -->
</head>

<body>
    <header>
        <div class="header-top">
            <div class="left-top-header">
                <div class="first"></div>
                <div class="second"></div>
                <div class="third"></div>
                <div class="fourth"></div>
                <div class="fifth"></div>
                <div class="sixth"></div>
                <div class="seventh"></div>
                <div class="eighth"></div>
                <div class="nineth"></div>
                <div class="tenth"></div>
                <div class="eleventh"></div>
                <div class="tweveth"></div>
            </div>
            <div id="google_element">
                <script src="http://translate.google.com/translate_a/element.js?cb=loadGoogleTranslate"></script>
                <script>
                function loadGoogleTranslate() {
                    new google.translate.TranslateElement("google_element");

                }
                </script>
            </div>

        </div>
        <hr>
    </header>

    <div class="container">
        <h2 class="text-center mb-4"><b><u>CHARAK SEVA HOSPITAL</u></b></h2><br><br>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="userid" required placeholder="Enter Valid User ID">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="fetch" class="btn btn-primary btn-small" type="button">VIEW DATA</button>
            </div>
        </form><br>

        <p>
            <b>Note:-</b> You can check your registerd details here. Just enter your valid User Id and show the details.
        </p>

        <?php if ($userData): ?>
        <div class="frameset">
            <h2 class="text-center"><b>YOU WELCOME DEAR,</b>
                <span
                    class="text-danger"><?= htmlspecialchars($userData['firstname'])." ". htmlspecialchars($userData['middlename'])." ". htmlspecialchars($userData['lastname'])?>
                </span>
            </h2>
            <form method="POST">
                <div class="frameset">
                    <h3 class="text-center mb-3 "><b><u>PERSONAL DEATILS</u></b></h3>
                    <div class="mb-3 text-center">
                        <label for=" profile" class="form-label">
                            <img src="<?php echo $profilePic; ?>" alt="failed to load image"
                                onerror="this.onerror=null;this.src='default-profile.jpg';"
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                        </label>

                    </div>
                    <input type="hidden" name="username" value="<?= htmlspecialchars($userData['username']) ?>">

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
                                value="<?= htmlspecialchars($userData['dob']) ?>" onchange="calculateAge()"
                                readrequired>
                            <span class="error" id="dobError"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="age" class="form-label"><b>CURRENT AGE</b></label>
                            <input type="text" class="form-control" id="age" name="age"
                                value="<?= htmlspecialchars($userData['age']) ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="mobile" class="form-label"><b>MOBILE NUMBER</b></label>
                            <input type="text" class="form-control" id="mobile" name="mobile" maxlength="10"
                                value="<?= htmlspecialchars($userData['mobile']) ?>" readonlyrequired>
                            <span class="error" id="mobileError"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="alt-mobile" class="form-label"><b>ALTER MOBILE NUMBER</b></label>
                            <input type="text" class="form-control" id="alt-mobile" name="alt-mobile" maxlength="10"
                                value="<?= htmlspecialchars($userData['amobile']) ?>" readonlyrequired>
                        </div>
                    </div>

                </div>

                <div class="frameset">

                    <h3 class="text-center mb-3 "><b><u>ADDRESS DETAILS</u></b></h3>
                    <div class="row mb-3">
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
                        <div class="col-md-4">
                            <label for="pincode" class="form-label"><b>PIN CODE</b></label>
                            <input type="text" class="form-control" id="pincode" maxlength="6" name="pincode"
                                value="<?= htmlspecialchars($userData['pincode']) ?>" readonly required>
                            <span class="error" id="pincodeError"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="district" class="form-label"><b>DISTRICT</b></label>
                            <input type="text" class="form-control" id="district" name="district"
                                value="<?= htmlspecialchars($userData['district']) ?>" readonly required>
                            <span class="error" id="districtError"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label"><b>STATE</b></label>
                            <input type="text" class="form-control" id="state" name="state"
                                value="<?= htmlspecialchars($userData['state']) ?>" readonly required>

                            <span class="error" id="stateError"></span>
                        </div>
                    </div>
                </div>

                <div class="frameset">
                    <h3 class="text-center mb-3 "><b><u>LOGIN DETAILS</u></b></h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label"><b>USERNAME</b></label> </label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?= htmlspecialchars($userData['username']) ?>" readonly required>
                            <span class="error" id="usernameError"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="password" class="form-label"><b>PASSWORD</b></label>
                            <input type="text" class="form-control" id="password" name="password"
                                value="<?= htmlspecialchars($userData['password']) ?>" readonly required>
                            <span class="error" id="passwordError"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="confirm-password" class="form-label"><b>CONFIRM PASSWORD</b></label>
                            <input type="text" class="form-control" id="confirm-password" readonly required>
                            <span class="error" id="confirmPasswordError"></span>
                        </div>
                    </div>
                </div>

                <div class=" d-grid gap-2 col-6 mx-auto">
                    <button type="button" class=" btn btn-info" id="editBtn" onclick="enableEdit()"><b>EDIT</b></button>
                    <button type="submit" class="btn btn-success " id="saveBtn" name="update"
                        style="display: none;"><b>SAVE</b></button>
                </div>
            </form>

        </div>

        <?php endif; ?>

        <script>
        function enableEdit() {
            document.querySelectorAll("input[readonly]").forEach(input => input.removeAttribute("readonly"));
            document.querySelectorAll("select[disabled]").forEach(select => select.removeAttribute("disabled"));
            document.getElementById("editBtn").style.display = "none";
            document.getElementById("saveBtn").style.display = "inline-block";
        }

        function calculateAge() {
            const dob = document.getElementById('dob').value;
            if (dob) {
                const today = new Date();
                const birthDate = new Date(dob);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                document.getElementById('age').value = age;
            }
        }

        document.getElementById('dob')?.addEventListener('change', calculateAge);

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').innerText = "Passwords do not match!";
            } else {
                document.getElementById('confirmPasswordError').innerText = "";
            }
        }

        document.getElementById('password')?.addEventListener('input', validatePasswordMatch);
        document.getElementById('confirm-password')?.addEventListener('input', validatePasswordMatch);
        </script>
</body>

</html>