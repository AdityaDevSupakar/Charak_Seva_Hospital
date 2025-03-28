<?php
// Database Connection
$con = mysqli_connect("localhost", "root", "", "d_hms");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Blood Donation Approval
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
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
    mysqli_close($con);
    exit; // Stop further execution for AJAX response
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
    <title>Staff Donate Blood</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="HMS.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
    html,
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overscroll-behavior-y: none;
    }
    </style>

    <script>
    function approveRequest(id) {
        if (confirm("Are you sure you want to approve this request?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "Staff Donate Blood.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    console.log("Response:", xhr.responseText); // Debugging

                    if (xhr.status == 200) {
                        alert(xhr.responseText); // Show message from PHP
                        window.location.reload();
                    } else {
                        alert("Server error: " + xhr.status);
                    }
                }
            };

            console.log("Sending ID:", id); // Debugging
            xhr.send("id=" + id);
        }
    }
    </script>

</head>

<body>
    <header>
        <div class="header-top">
            <div class="left-top-header"></div>
        </div>
        <hr>
    </header>

    <section class="bg-body-secondary">
        <div class="row g-4">
            <h1 class="text-center font-arial-black"><b><u>DONATE BLOOD LISTS</u></b></h1>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
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
                        </tr>
                        <?php } ?>
                    </tbody>
                </table><br>
            </div><br>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Q3RrrTnIta5RtZjBmbnDNXtbqGJeDV+5TZgePGGjwrUt4p3Ni5h0dC8I0kdyB+4U" crossorigin="anonymous">
    </script>
</body>

</html>

<?php mysqli_close($con); ?>