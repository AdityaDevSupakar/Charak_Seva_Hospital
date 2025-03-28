<?php
// Database Connection
$con = mysqli_connect("localhost", "root", "", "d_hms");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Blood Donation Approval
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) { // Approve Request
        $id = intval($_POST['id']); // Ensure ID is an integer

        if ($id > 0) {
            $query = "UPDATE donate_blood SET status = 'Approved' WHERE sr_no = ?";
            $stmt = mysqli_prepare($con, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                if (mysqli_stmt_execute($stmt)) {
                    echo "SUCCESS: Request Approved!";
                } else {
                    echo "ERROR: Failed to update. " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "ERROR: Failed to prepare statement. " . mysqli_error($con);
            }
        } else {
            echo "ERROR: Invalid ID received.";
        }
        exit;
    }

    if (isset($_POST['remove_id'])) { // Remove Request
    header('Content-Type: application/json'); // Ensure JSON response
    $remove_id = intval($_POST['remove_id']);

    if ($remove_id > 0) {
        $query = "DELETE FROM donate_blood WHERE sr_no = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $remove_id);
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(["status" => "success", "message" => "SUCCESS: Record deleted!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "ERROR: Failed to delete."]);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(["status" => "error", "message" => "ERROR: Failed to prepare statement."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ERROR: Invalid ID received."]);
    }
    exit;
}


}

// Fetch Blood Donation Requests
$query = "SELECT * FROM donate_blood";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Donate Blood</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <script>
    function approveRequest(id) {
        if (confirm("Are you sure you want to approve this request?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "Staff Donate Blood.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        alert(xhr.responseText);
                        window.location.reload();
                    } else {
                        alert("Server error: " + xhr.status);
                    }
                }
            };
            xhr.send("id=" + id);
        }
    }

    function removeRequest(id) {
        if (confirm("Are you sure you want to remove this record?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "Staff Donate Blood.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        try {
                            var response = JSON.parse(xhr.responseText); // Parse JSON response
                            if (response.status === "success") {
                                alert(response.message);
                                document.getElementById("row-" + id).remove(); // Remove row if successful
                            } else {
                                alert(response.message); // Show error message
                            }
                        } catch (e) {
                            alert("Unexpected server response.");
                        }
                    } else {
                        alert("Server error: " + xhr.status);
                    }
                }
            };
            xhr.send("remove_id=" + id);
        }
    }
    </script>
    <style>
    * {
        margin: 0;
        padding: 0;
        overscroll-behavior-x: none;
        overscroll-behavior-y: none;

    }
    </style>
</head>

<body>
    <section class="bg-body-secondary">
        <h1 class="text-center"><b><u>DONATE BLOOD LISTS</u></b></h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>S.N</th>
                        <th>NAME</th>
                        <th>FATHER_NAME</th>
                        <th>DATE_OF_BIRTH</th>
                        <th>BLOOD_GROUP</th>
                        <th>ADDRESS</th>
                        <th>MOBILE_NUMBER</th>
                        <th>BOOKED_DATE</th>
                        <th>STATUS</th>
                        <th>APPROVE</th>
                        <th>REMOVE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr id="row-<?php echo $row['sr_no']; ?>">
                        <td><?php echo htmlspecialchars($row['sr_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['father_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['dob']); ?></td>
                        <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                        <td><?php echo htmlspecialchars($row['booked_at']); ?></td>
                        <td>
                            <b><?php echo ($row['status'] == 'Approved') ? '<span class="text-success">Approved</span>' : '<span class="text-warning">Pending</span>'; ?></b>
                        </td>
                        <td>
                            <?php if ($row['status'] != 'Approved') { ?>
                            <button class="btn btn-primary btn-sm"
                                onclick="approveRequest(<?php echo $row['sr_no']; ?>)">APPROVE</button>
                            <?php } else { ?>
                            <button class="btn btn-secondary btn-sm" disabled>APPROVED</button>
                            <?php } ?>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm"
                                onclick="removeRequest(<?php echo $row['sr_no']; ?>)">REMOVE</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php mysqli_close($con); ?>