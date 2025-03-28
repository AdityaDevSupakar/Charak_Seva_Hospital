<?php
$con = mysqli_connect("localhost", "root", "", "d_hms");
if (!$con) {
    die("Sorry, something went wrong connecting to the database.");
}
$query = "SELECT * FROM oxygen_requests";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OXYGEN REQUESTS LIST</title>

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
            <h1 class="text-center font-arial-black"><b><u>OXYGEN REQUESTS LIST</u></b></h1>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>S.N</th>
                            <th>PATIENT's NAME</th>
                            <th>FATHER's NAME</th>
                            <th>MOTHER's NAME</th>
                            <th>MOBILE</th>
                            <th>DATE_OF_BIRTH</th>
                            <th>GENDER</th>
                            <th>VILLAGE</th>
                            <th>POST</th>
                            <th>PIN_CODE</th>
                            <th>DISTRICT</th>
                            <th>STATE</th>
                            <th>BED_NUMBER</th>
                            <th>OXYGEN_AMOUNT</th>
                            <th>ADMIT_DATE</th>
                            <th>MESSAGE</th>
                            <th>ACCEPT</th>
                            <th>REJECT</th>
                            <th>REMOVE</th>
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


                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['father_name']; ?></td>
                            <td><?php echo $row['mother_name']; ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['village']; ?></td>
                            <td><?php echo $row['post']; ?></td>
                            <td><?php echo $row['pincode']; ?></td>
                            <td><?php echo $row['district']; ?></td>
                            <td><?php echo $row['state']; ?></td>
                            <td><?php echo $row['bed_number']; ?></td>
                            <td><?php echo $row['amount_oxygen']; ?></td>
                            <td><?php echo $row['admit_date']; ?></td>
                            <td><?php echo $row['message']; ?></td>

                            <td><button class="btn btn-danger btn-sm">ACCEPT</button></td>
                            <td><button class="btn btn-danger btn-sm">REJECT</button></td>
                            <td><button class="btn btn-danger btn-sm">REMOVE</button></td>

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