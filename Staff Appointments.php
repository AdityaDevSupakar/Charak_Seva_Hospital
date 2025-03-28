<?php
$con = mysqli_connect("localhost", "root", "", "d_hms");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $id = mysqli_real_escape_string($con, $_POST['delete_id']);
        $query = "DELETE FROM appointments WHERE sr_no = '$id'";
        mysqli_query($con, $query);
        exit;
    }

    if (isset($_POST['edit_id'])) {
        $id = mysqli_real_escape_string($con, $_POST['edit_id']);
        $updateFields = [];
        foreach ($_POST as $key => $value) {
            if ($key !== 'edit_id') {
                $safeValue = mysqli_real_escape_string($con, $value);
                $updateFields[] = "$key = '$safeValue'";
            }
        }
        if (!empty($updateFields)) {
            $query = "UPDATE appointments SET " . implode(", ", $updateFields) . " WHERE sr_no='$id'";
            mysqli_query($con, $query);
        }
        exit;
    }
}

$query = "SELECT * FROM appointments";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APPOINTMENTS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
    html,
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .reason-td {
        min-width: 250px;
        max-width: 450px;
        word-wrap: break-word;
        white-space: normal;
    }

    .rock {
        min-width: 150px;
        max-width: 250px;
        word-wrap: break-word;
        white-space: normal;
    }

    .form-control {
        width: 100%;
    }
    </style>
</head>

<body>
    <section class="bg-body-secondary">
        <h1 class="text-center"><b><u>Patient's Appointment List</u></b></h1>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>S.N</th>
                        <th>FIRST_NAME</th>
                        <th>MIDDLE_NAME</th>
                        <th>LAST_NAME</th>
                        <th>F_FIRST_NAME</th>
                        <th>F_MIDDLE_NAME</th>
                        <th>F_LAST_NAME</th>
                        <th>M_FIRST_NAME</th>
                        <th>M_MIDDLE_NAME</th>
                        <th>M_LAST_NAME</th>
                        <th>GENDER</th>
                        <th>DATE_OF_BIRTH</th>
                        <th>CURRENT_AGE</th>
                        <th>MOBILE_NUMBER</th>
                        <th>VILLAGE</th>
                        <th>POST</th>
                        <th>PIN_CODE</th>
                        <th>DIST</th>
                        <th>REASON</th>
                        <th>JOINED_DATE</th>
                        <th>LAST_MODIFIED</th>
                        <th>MODIFY</th>
                        <th>REMOVE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr data-id="<?php echo $row['sr_no']; ?>">
                        <td><?php echo $row['sr_no']; ?></td>
                        <td><input type="text" class="form-control" name="firstname"
                                value="<?php echo $row['firstname']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="middlename"
                                value="<?php echo $row['middlename']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="lastname"
                                value="<?php echo $row['lastname']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="f_firstname"
                                value="<?php echo $row['f_firstname']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="f_middlename"
                                value="<?php echo $row['f_middlename']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="f_lastname"
                                value="<?php echo $row['f_lastname']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="m_firstname"
                                value="<?php echo $row['m_firstname']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="m_middlename"
                                value="<?php echo $row['m_middlename']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="m_lastname"
                                value="<?php echo $row['m_lastname']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="gender" value="<?php echo $row['gender']; ?>"
                                disabled></td>
                        <td><input type="date" class="form-control" name="dob" value="<?php echo $row['dob']; ?>"
                                disabled></td>
                        <td><input type="text" class="form-control" name="age" value="<?php echo $row['age']; ?>"
                                disabled></td>
                        <td><input type="text" class="form-control" name="mobile" value="<?php echo $row['mobile']; ?>"
                                disabled></td>
                        <td class="rock"><input type="text" class="form-control" name="village"
                                value="<?php echo $row['village']; ?>" disabled></td>
                        <td class="rock"><input type="text" class="form-control" name="post"
                                value="<?php echo $row['post']; ?>" disabled></td>
                        <td><input type="text" class="form-control" name="pincode"
                                value="<?php echo $row['pincode']; ?>" disabled></td>
                        <td class="rock"><input type="text" class="form-control" name="dist"
                                value="<?php echo $row['dist']; ?>" disabled></td>
                        <td class="reason-td"><input type="text" class="form-control width=30%" name="reason"
                                value="<?php echo $row['reason']; ?>" disabled></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['last_modified']; ?></td>
                        <td><button class="btn btn-primary btn-sm edit-btn">Edit</button><button
                                class="btn btn-success btn-sm save-btn d-none">Save</button></td>
                        <td><button class="btn btn-danger btn-sm delete-btn">Delete</button></td>

                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="Book%20Slot.html" class="btn btn-secondary w-50">Add New</a>
        </div>
    </section>

    <script>
    $(document).ready(function() {
        $(".edit-btn").click(function() {
            let row = $(this).closest("tr");
            row.find("input").prop("disabled", false);
            row.find(".edit-btn").addClass("d-none");
            row.find(".save-btn").removeClass("d-none");
            alert("Editing mode enabled. You can now update the fields.");
        });

        $(".save-btn").click(function() {
            let row = $(this).closest("tr");
            let id = row.attr("data-id");
            let formData = {
                edit_id: id
            };

            row.find("input").each(function() {
                formData[$(this).attr("name")] = $(this).val();
            });

            $.post("", formData, function() {
                row.find("input").prop("disabled", true);
                row.find(".edit-btn").removeClass("d-none");
                row.find(".save-btn").addClass("d-none");
                alert("Changes saved successfully.");
            });
        });

        $(".delete-btn").click(function() {
            let row = $(this).closest("tr");
            let id = row.attr("data-id");
            if (confirm("Are you sure you want to delete this record?")) {
                $.post("", {
                    delete_id: id
                }, function() {
                    row.remove();
                    alert("Record deleted successfully.");
                });
            }
        });
    });
    </script>

</body>

</html>