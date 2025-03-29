<?php
$con = mysqli_connect("localhost", "root", "", "d_hms");
if (!$con) {
    die("Sorry, something went wrong connecting to the database.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action'];

    if ($action == 'approve') {
        $query = "UPDATE book_ambulance SET status='Approved' WHERE sr_no='$id'";
    } elseif ($action == 'decline') {
        $query = "UPDATE book_ambulance SET status='Declined' WHERE sr_no='$id'";
    } elseif ($action == 'delete') {
        $query = "DELETE FROM book_ambulance WHERE sr_no='$id'";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Action performed successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
    } else {
        echo "<script>alert('Error performing action.');</script>";
    }
}

$query = "SELECT * FROM book_ambulance";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMBULANCE REQUESTS LIST</title>

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

    #b1 {
        border-radius: 5;
    }
    </style>
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
                <div class="ninth"></div>
                <div class="tenth"></div>
                <div class="eleventh"></div>
                <div class="twelfth"></div>
            </div>
        </div>
        <hr>
    </header>

    <section class="bg-body-secondary">
        <div class="row g-4">
            <h1 class="text-center font-arial-black"><b><u>AMBULANCE REQUIRED LISTS</u></b></h1>
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
                            <th>PLACE</th>
                            <th>LANDMARK</th>
                            <th>AREA</th>
                            <th>STREET</th>
                            <th>REQUESTED_DATE</th>
                            <th>STATUS</th>
                            <th>ACCEPT</th>
                            <th>REJECT</th>
                            <!-- <th>REMOVE</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          while ($row = mysqli_fetch_assoc($result)) {
                              // Set default image if profile_pic is empty or the file doesn't exist
                              $profilePic = !empty($row['profile_pic']) && file_exists($row['profile_pic']) ? $row['profile_pic'] : 'default-profile.jpg';
                       ?>
                        <tr>
                            <td><?php echo $row['sr_no']; ?></td>
                            <!-- <td>
                               <?php 
                                    $photo_path = !empty($photo_path) ? $photo_path : 'Uploads/default-profile.jpg'; 
                               ?>
                                   <img src="<?php echo htmlspecialchars($photo_path); ?>" 
                                        alt="Profile Picture" onerror="this.onerror=null;this.src='Uploads/';" 
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                           </td> -->


                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['middlename']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['place']; ?></td>
                            <td><?php echo $row['landmark']; ?></td>
                            <td><?php echo $row['area']; ?></td>
                            <td><?php echo $row['street']; ?></td>
                            <td><?php echo $row['booked_at']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="post" id="action-form-<?php echo $row['sr_no']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $row['sr_no']; ?>">
                                    <input type="hidden" name="action" id="action-<?php echo $row['sr_no']; ?>">
                                    <button type="button"
                                        onclick="confirmAction('approve', '<?php echo $row['sr_no']; ?>')"
                                        class="btn btn-success btn-sm">Approve</button>
                            </td>
                            <td>
                                <button type="button" onclick="confirmAction('decline', '<?php echo $row['sr_no']; ?>')"
                                    class="btn btn-secondary btn-sm">Decline</button>
                            </td>
                            <!-- <td>
                                <button type="button" onclick="confirmAction('delete', '<?php echo $row['sr_no']; ?>')"
                                    class="btn btn-danger btn-sm">Delete</button> -->
                            </form>
                            <!-- </td> -->
                        </tr>
                        <?php
                          }
                        ?>
                    </tbody>
                </table><br>
                <!-- <a href="http://localhost/Charak%20Seva%20Hospital/Book%20Slot.html" class="btn btn-secondary w-50" 
                   style="border-radius: 15px; border-color: red; padding: 10px 20px; justify-content: center; align-items: center;">
                   Add New
                </a>               -->
            </div><br>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Q3RrrTnIta5RtZjBmbnDNXtbqGJeDV+5TZgePGGjwrUt4p3Ni5h0dC8I0kdyB+4U" crossorigin="anonymous">
    </script>
</body>

</html>