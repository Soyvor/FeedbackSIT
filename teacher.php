<?php
session_start();
require_once "connection.php";
if (!isset($_SESSION["Teacher"])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION["Teacher"];
$user_name = $username;
// Remove everything after the "@" symbol
$username = substr($username, 0, strpos($username, "@"));

// Split the username into an array based on the "." delimiter
$name_array = explode(".", $username);

// Capitalize the first letter of each name part and join with a space
$final = ucwords(implode(" ", $name_array));

$query_name = "SELECT * FROM login WHERE username = '$user_name' LIMIT 1";

// execute the query and fetch the result
$result = mysqli_query($conn, $query_name);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// extract the name value from the row and store it in $name variable
// $name = $row['name'];




// Define the username variable
$username = $user_name;

// Define an empty array to store unique combinations of email, branch, and subject
$unique_entries = array();

$branch_tables = array("cs_teacher", "mech_teacher"); // Add more branch-specific tables as needed

foreach ($branch_tables as $branch_table) {
    $query = "SELECT email, subject, branch, acad_year FROM $branch_table WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($email, $subject, $branch, $acad_year);
    while ($stmt->fetch()) {
        if (!in_array(array($email, $branch, $subject, $acad_year), $unique_entries)) {
            $unique_entries[] = array($email, $branch, $subject, $acad_year);
        }
    }
    $stmt->close();
}

$avg_avg = array();

$query_name = "SELECT * FROM cs_teacher WHERE email = '$email' LIMIT 1";
$final_email = $email;
$branch_final = $branch;
// execute the query and fetch the result
$result = mysqli_query($conn, $query_name);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$name = $row['name'];




?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Xtreme lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Xtreme admin lite design, Xtreme admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description" content="Xtreme Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Teacher Dashboard</title>

    <!-- Favicon icon -->
    <!-- <link rel="icon" type="image/png" sizes="16x16" href="./Coordinator Dashboard css and js /assets/images/favicon.png"> -->
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

    <!-- Include Footer CSS -->
    <link rel="stylesheet" href="./css/footer_style.css">



    <!-- jQuery -->
    <script src="./js/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <style>
        .custom-in-card-circle {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            background: #47535C;
            ;
            box-shadow: 0px 0px 28px 5px rgba(0, 0, 0, 0.30);

        }

        .tab-content:not(#tab1) {
            display: none;
        }

        .sidebar-item a.active-nav {
            color: red;
            font-weight: bold;

        }

        .active {
            background: rgba(217, 217, 217, 0.09);
            box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.25);
        }



        .tab-content.active {
            display: block;
        }

        @media (max-width: 1170px) {
            .active {
                background-color: transparent;
                box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);
            }
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }

        .card-link:hover {
            text-decoration: none;
            color: inherit;

        }

        .card {
            min-height: 190px;


            background-color: #202E39;
            border-radius: 1.25rem;
        }

        .card:hover {
            background-color: #3e5569;
            box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.25);
        }





        .btn {

            border: 0;
            border-radius: 10px;

            min-width: 110px;
            border-radius: 22px;

            color: #FFF;
            font-size: 16px;
            font-weight: 700;
        }

        .btn-primary {
            background: #3D8BFD;

        }

        .table thead th {
            color: white;
            font-size: medium;
            /* Example background color */
        }

        td {
            color: #FFF;
        }

        .table-scroll {
            overflow-x: auto;
            overflow-y: auto;
            max-width: 100%;
        }
    </style>



    <script src="./js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Hide all tab contents except the first one
            $('.tab-content:not(:first)').hide();

            // Add click event handler to the sidebar items
            $('.sidebar-item').click(function() {
                // Remove active class from all sidebar items
                $('.sidebar-item').removeClass('active');
                // Add active class to the clicked sidebar item
                $(this).addClass('active');

                // Hide all tab contents
                $('.tab-content').hide();

                // Show the corresponding tab content based on the clicked item
                var tabToShow = $(this).attr('data-tab');
                $('#' + tabToShow).show();
            });
        });
    </script>

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- Hamburger Mobile View -->
        <a class="m-2 nav-toggler waves-effect waves-light d-md-none" style="position: absolute;z-index:100;" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>

        <aside class="left-sidebar pt-0" data-sidebarbg="skin6" style="background: #202E39;">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li>
                            <!-- User Profile-->
                            <div class="user-profile d-flex no-block dropdown ">
                                <div class="user-content hide-menu m-l-10">
                                    <a href="#" class="" id="Userdd" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class=" user-email"> <?php echo $final; ?></span>
                                    </a>
                                </div>
                            </div>
                            <!-- End User Profile-->
                        </li>


                        <!-- User Profile -->
                        <li class="sidebar-item item1 active" data-tab="tab1">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-clipboard-text"></i><span class="hide-menu">View Feedback</span>
                            </a>
                        </li>

                        <li class="sidebar-item item2" data-tab="tab2">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-account-star"></i><span class="hide-menu">Assign Guest</span>
                            </a>
                        </li>

                        <li class="sidebar-item item2" data-tab="tab3">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-eye"></i><span class="hide-menu">View Guest Feedback</span>
                            </a>
                        </li>



                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <div class="page-breadcrumb py-2" style=" background: #212f3e;;
            box-shadow: 0px 4px 66px 0px rgba(0, 0, 0, 0.15);">
                <div class="row  align-items-center">
                    <div class="col-3">
                        <h4 class="page-title op-5" style="color:white ;font-size:25px">Dashboard</h4>
                    </div>
                    <div class="col-9">
                        <div class="text-end upgrade-btn">

                            <form action="logout.php" method="POST" style="display: inline;">
                                <a href="logout.php"><button type="submit" name="logout" class="btn btn-primary text-white">Logout</button></a>
                            </form>

                            <a href="reset.php" class="btn btn-primary text-white" target="_blank">Reset Password</a>

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid " style="background-color:#17212C;color: white; min-height:calc(100vh - 149px)">



                <!-- Content for Tab 1 -->
                <div id="tab1" class="tab-content">
                    <h2>Feedback</h2>
                    <br>
                    <div class="row main-content">
                        <div class="table-scroll">
                            <?php
                            // Start the table and output the header row
                            echo '<table class="table ">';
                            echo '<thead><tr><th>Email</th><th>Year - Branch</th><th>Subject</th><th>Average Feedback Score</th></tr></thead>';
                            echo '<tbody>';

                            // Loop over each unique combination of email, branch, and subject
                            $feedbackTables = ['cs_feedback', 'mech_feedback']; // Add more table names as needed

                            foreach ($unique_entries as $entry) {
                                $email = $entry[0];
                                $branch = $entry[1];
                                $subject = $entry[2];
                                $acad_year = $entry[3];
                                $found = false; // Flag to track if valid results are found for this entry

                                foreach ($feedbackTables as $table) {
                                    $query_match = "SELECT * FROM $table WHERE teacher = '$name' AND branch = '$branch' AND acad_year = '$acad_year' AND subject = '$subject'";
                                    $result_match = mysqli_query($conn, $query_match);
                                    $sum = 0;
                                    $count = 0;

                                    if (mysqli_num_rows($result_match) > 0) {
                                        $found = true; // Mark as found if at least one valid result exists

                                        while ($row = mysqli_fetch_assoc($result_match)) {
                                            $sum += $row['avg'];
                                            $count++;
                                        }

                                        $average = ($count > 0) ? ($sum / $count) : 0;

                                        // Output the row for this combination of email, branch, and subject
                                        echo "<tr class='table-row-hover'><td>$email</td><td>$acad_year-$branch</td><td>$subject</td><td>$average</td></tr>";
                                    }
                                }

                                // Output the row only if valid results were found
                                if (!$found) {
                                    echo "<tr class='table-row-hover'><td>$email</td><td>$acad_year-$branch</td><td>$subject</td><td>No data</td><td>0</td></tr>";
                                }
                            }





                            // End the table
                            echo '</tbody></table>';
                            ?>
                        </div>
                    </div>


                </div>


                <div id="tab2" class="tab-content">
                    <h2>Guest Manager</h2>
                    <br>
                    <div class="row main-content">
                        <!-- <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <label for="guestName">Guest Name:</label>
                            <input type="text" name="guestName" required>
                            <br>
                            <label for="guestDate">Guest Date:</label>
                            <input type="date" name="guestDate" required>
                            <br>
                            <label for="guestTopic">Guest Topic:</label>
                            <input type="text" name="guestTopic" required>
                            <br>
                            <label for="branch">Branch:</label>
                            <input type="text" name="branch" required>
                            <br>
                            <label for="csvfile_guest_data">Select CSV file for students:</label>
                            <input class="ChooseFile" type="file" name="csvfile_guest_data" id="csvfile_guest_data"><br>
                            <input class="UploadButton" type="submit" name="submit1" value="Upload">
                        </form> -->
                        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="form-group">
                                <label for="guestName">Guest Name:</label>
                                <input type="text" class="form-control" name="guestName" required style="max-width:400px">
                            </div>
                            <div class="form-group">
                                <label for="guestDate">Guest Date:</label>
                                <input type="date" class="form-control" name="guestDate" required style="max-width:400px">
                            </div>
                            <div class="form-group">
                                <label for="guestTopic">Guest Topic:</label>
                                <input type="text" class="form-control" name="guestTopic" required style="max-width:400px">
                            </div>
                            <div class="form-group">
                                <label for="branch">Branch:</label>
                                <input type="text" class="form-control" name="branch" required style="max-width:400px">
                            </div>
                            <div class="form-group">
                                <label for="csvfile_guest_data">Select CSV file for students:</label>
                                <input class="form-control-file" type="file" name="csvfile_guest_data" id="csvfile_guest_data">
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit1">Upload</button>
                        </form>



                        <script>
                            <?php
                            if (isset($_SESSION['update_success'])) {
                                if ($_SESSION['update_success']) {
                                    echo "alert('Guest status updated successfully');";
                                } else {
                                    echo "alert('Error updating guest status');";
                                }
                                unset($_SESSION['update_success']); // Clear the session variable
                            }
                            ?>
                        </script>
                    </div>
                </div>

                <div id="tab3" class="tab-content">
                    <h2>Guest Feedback</h2>
                    <br>
                    <div class="row main-content">
                        <div class="table-scroll">



                            <?php

                            echo '<table class="table ">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Guest Name</th>';
                            echo '<th>Guest Topic</th>';
                            echo '<th>Guest Date</th>';
                            echo '<th>Feedback</th>';
                            echo '<th>Download Feedback</th>';
                            echo '<th>Turn ON/OFF</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            $query_guests = "SELECT * FROM guest WHERE teacher_email = '$final_email'";
                            $result_guests = mysqli_query($conn, $query_guests);

                            while ($guest_row = mysqli_fetch_assoc($result_guests)) {
                                $guest_id = $guest_row['id'];
                                $guest_name = $guest_row['guest_name'];
                                $guest_topic = $guest_row['guest_topic'];
                                $guest_date = $guest_row['guest_date'];

                                // Get feedback details for this guest
                                $query_feedback = "SELECT AVG(avg) AS average, COUNT(*) AS count FROM guest_feedback WHERE guest_id = '$guest_id'";
                                $result_feedback = mysqli_query($conn, $query_feedback);
                                $feedback_row = mysqli_fetch_assoc($result_feedback);
                                $average_feedback = $feedback_row['average'];
                                $feedback_count = $feedback_row['count'];

                                echo "<tr>";
                                echo "<td>$guest_name</td><td>$guest_topic</td><td>$guest_date</td>";
                                echo "<td>Average: $average_feedback<br>Feedback Count: $feedback_count</td>";
                                echo "<td><button onclick=\"location.href='download_guest_feedback.php?guest_id=$guest_id&guest_name=$guest_name'\">Download</button></td>";

                                echo "<td><button onclick=\"location.href='toggle_guest_status.php?guest_id=$guest_id'\">Turn ON/OFF</button></td>";

                                echo "</tr>";
                                
                            }


                            echo "</table>";
                            ?>
                            <script>
                                <?php
                                if (isset($_SESSION['update_success'])) {
                                    if ($_SESSION['update_success']) {
                                        echo "alert('Guest status updated successfully');";
                                    } else {
                                        echo "alert('Error updating guest status');";
                                    }
                                    unset($_SESSION['update_success']); // Clear the session variable
                                }
                                ?>
                            </script>
                        </div>
                    </div>
                </div>


                <?php
                if (isset($_POST['submit1'])) {
                    // Get form inputs
                    $guestName = $_POST['guestName'];
                    $guestDate = $_POST['guestDate'];
                    $guestTopic = $_POST['guestTopic'];
                    $branch = $_POST['branch'];

                    // Insert guest data into guest table
                    $insertGuestQuery = "INSERT INTO guest (guest_name, guest_date, guest_topic, teacher_email, branch, is_valid) 
                         VALUES ('$guestName', '$guestDate', '$guestTopic','$final_email', '$branch',1)";
                    mysqli_query($conn, $insertGuestQuery);

                    // Process uploaded CSV file
                    if (!empty($_FILES['csvfile_guest_data']['name'])) {
                        $file_name = $_FILES['csvfile_guest_data']['name'];
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                        if ($file_ext !== 'csv') {
                            echo "<script>alert('Invalid File Type! Please upload a CSV file.');</script>";
                        } else {
                            // Open uploaded file
                            $file = fopen($_FILES['csvfile_guest_data']['tmp_name'], 'r');

                            // Skip first line (header)
                            fgetcsv($file);

                            // Get the ID of the inserted guest
                            $guestId = mysqli_insert_id($conn);

                            // Loop through each row of the CSV file
                            while ($row = fgetcsv($file)) {
                                // Sanitize and validate each field
                                $studentEmail = sanitizeAndValidateEmail($row[0]);

                                // Insert student-guest relationship into guest_student table
                                $insertGuestStudentQuery = "INSERT INTO guest_student (guest_id, student_email, is_valid) 
                                           VALUES ('$guestId', '$studentEmail',1)";
                                mysqli_query($conn, $insertGuestStudentQuery);
                            }

                            echo "<script>alert('File Uploaded successfully!');</script>";

                            // Close file
                            fclose($file);
                        }
                    }
                }


                // Your sanitizeAndValidate functions go here...




                function sanitizeAndValidatePrn($prn)
                {
                    $prn = preg_replace('/[^0-9]/', '', $prn); // Remove non-digit characters
                    if (strlen($prn) !== 11 || !ctype_digit($prn)) {
                        echo "<script>alert('Invalid PRN: $prn');</script>";
                        // Handle the error (e.g., log, display, etc.)
                        return false;
                    } else {
                        return $prn;
                    }
                }

                function sanitizeAndValidateName($name)
                {
                    $name = trim(preg_replace('/[^a-zA-Z ]/', '', $name)); // Remove non-alphabet and space characters and trim spaces
                    return $name;
                }

                function sanitizeAndValidateEmail($email)
                {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo "<script>alert('Invalid email: $email');</script>";
                        // Handle the error (e.g., log, display, etc.)
                        return false; // Skip the iteration if the email is invalid
                    } else {
                        return $email;
                    }
                }

                function sanitizeAndValidateVarchar($varchar)
                {
                    $varchar = preg_replace('/[^a-zA-Z ]/', '', $varchar); // Remove non-alphabet and space characters
                    return $varchar;
                }

                function sanitizeAndValidateAcadYear($acad_year)
                {
                    $allowedYears = ['fy', 'sy', 'ty', 'fly'];
                    if (!in_array(strtolower($acad_year), $allowedYears)) {
                        echo "<script>alert('Invalid academic year: $acad_year');</script>";
                        // Handle the error (e.g., log, display, etc.)
                        return false; // Skip the iteration if the academic year is invalid
                    } else {
                        return strtolower($acad_year);
                    }
                }

                function sanitizeAndValidateBranch($branch)
                {
                    $branch = preg_replace('/[^a-zA-Z]/', '', $branch); // Remove non-alphabet characters
                    return strtolower($branch);
                }

                function sanitizeAndValidateClass($class)
                {
                    $class = preg_replace('/[^a-zA-Z0-9]/', '', $class); // Remove non-alphabet characters
                    return strtolower($class);
                }

                function sanitizeAndValidateSemester($sem)
                {
                    if (!ctype_digit($sem) || $sem < 1 || $sem > 8) {
                        echo "<script>alert('Invalid semester: $sem');</script>";
                        // Handle the error (e.g., log, display, etc.)
                        return false; // Skip the iteration if the semester is invalid
                    } else {
                        return $sem;
                    }
                }


                ?>





                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->



            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->

            <footer class="site-footer footer-bottom d-flex py-1">

                <div class="container" style="min-width:100%">
                    <hr class="mt-3 mb-2" style="color: #CDCDCD;">
                    <div class="row">
                        <div class="col-xs-12 col-md-4 copyright-text">


                            <p style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Feedback |
                                <a>Copyright &copy; 2023 </a>
                            </p>
                        </div>

                        <div class="col-xs-12 col-md-4 ">


                            <ul class="footer-links text-center">

                                <li><a style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Ideation By Dr. Deepali Vora, Head CS IT</a></li>


                            </ul>
                        </div>

                        <div class="col-xs-12 col-md-4 ">

                            <ul class="footer-links text-right custom-developed-by">
                                <li><a style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Developed By: </a>
                                    <a href="https://www.linkedin.com/in/skp2208/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Swayam Pendgaonkar</a>
                                </li>
                                <li><a href="https://www.linkedin.com/in/sakshamgupta912/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Saksham Gupta </a>
                                    <a href="https://www.linkedin.com/in/yajushreshtha-shukla/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Yajushreshtha Shukla</a>
                                </li>

                            </ul>
                        </div>

                    </div>

                </div>

        </div>
        </footer>

    </div>


    </div>


    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="./Coordinator Dashboard css and js/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="./Coordinator Dashboard css and js/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./Coordinator Dashboard css and js/dist/js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="./Coordinator Dashboard css and js/dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="./Coordinator Dashboard css and js/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="./Coordinator Dashboard css and js/dist/js/custom.js"></script>
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="./Coordinator Dashboard css and js/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="./Coordinator Dashboard css and js/dist/js/pages/dashboards/dashboard1.js"></script>

    <script src="./js/jquery-3.6.0.min.js">
    </script>


</body>

</html>