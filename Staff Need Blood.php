<?php
$con = mysqli_connect("localhost", "root", "", "d_hms");
if (!$con) {
    die("Sorry, something went wrong connecting to the database.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action'];

    if ($action == 'approve') {
        $query = "UPDATE blood_requests SET status='Approved' WHERE sr_no='$id'";
    } elseif ($action == 'decline') {
        $query = "UPDATE blood_requests SET status='Declined' WHERE sr_no='$id'";
    } elseif ($action == 'delete') {
        $query = "DELETE FROM blood_requests WHERE sr_no='$id'";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Action performed successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
    } else {
        echo "<script>alert('Error performing action.');</script>";
    }
}

$query = "SELECT * FROM blood_requests";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLOOD REQUESTS LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
    function confirmAction(action, id) {
        if (confirm("Are you sure you want to " + action + " this request?")) {
            document.getElementById('action-' + id).value = action; // Set action value
            document.getElementById('action-form-' + id).submit();
        }
    }
    </script>
</head>

<body>
    <section class="bg-body-secondary">
        <div class="container mt-5">
            <h1 class="text-center"><b><u>BLOOD REQUESTS LIST</u></b></h1>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>S.N</th>
                            <th>FIRST_NAME</th>
                            <th>MIDDLE_NAME</th>
                            <th>LAST_NAME</th>
                            <th>GENDER</th>
                            <th>AGE</th>
                            <th>MOBILE_NUMBER</th>
                            <th>BLOOD_GROUP</th>
                            <th>USER_NAME</th>
                            <th>HOSPITAL</th>
                            <th>REQUESTED_DATE</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['sr_no']; ?></td>
                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['middlename']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['hospital']; ?></td>
                            <td><?php echo $row['request_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="post" id="action-form-<?php echo $row['sr_no']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $row['sr_no']; ?>">
                                    <input type="hidden" name="action" id="action-<?php echo $row['sr_no']; ?>">
                                    <button type="button"
                                        onclick="confirmAction('approve', '<?php echo $row['sr_no']; ?>')"
                                        class="btn btn-success btn-sm">Approve</button>
                                    <button type="button"
                                        onclick="confirmAction('decline', '<?php echo $row['sr_no']; ?>')"
                                        class="btn btn-secondary btn-sm">Decline</button>
                                    <button type="button"
                                        onclick="confirmAction('delete', '<?php echo $row['sr_no']; ?>')"
                                        class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>