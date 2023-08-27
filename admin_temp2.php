<?php
session_start();
if (!isset($_SESSION['SuperAdmin'])) {
    header('Location: index.php');
    exit;
}


require_once "connection.php";

$username = $_SESSION['SuperAdmin'];
$username_parts = explode("@", $username);
$username_prefix = $username_parts[0];
$text = strtolower($username_prefix); // convert all characters to lowercase
$text_final = ucfirst($text);


if (!$conn) {
    die('Error connecting to the database: ' . mysqli_connect_error());
}

// add a new coordinator
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coordinator'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $branch = $_POST['branch'];

    // validate email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // hash the password
        $encoded_string = base64_encode($password);


        // insert the new coordinator into the database
        $sql = "INSERT INTO login (username, password, branch, role, crnt_year, is_valid) VALUES ('$email', '$encoded_string', '$branch','coordinator','2023','1')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Coordinator added successfully!');</script>";
        } else {
            $error_message = mysqli_error($conn);
            echo "<script>alert('Error adding coordinator: $error_message');</script>";
        }
    } else {
        echo "<script>alert('Invalid email address!');</script>";
    }
}

// delete an existing coordinator
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_coordinator'])) {
    $email = $_POST['email'];

    // delete the coordinator from the database
    $sql = "DELETE FROM login WHERE username='$email'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Coordinator deleted successfully!');</script>";
    } else {
        $error_message1 = mysqli_error($conn);
        echo "<script>alert('Error deleting coordinator: $error_message1');</script>";
    }
}

// get all the coordinators from the database
$sql = "SELECT * FROM login WHERE role = 'coordinator'";
$result = mysqli_query($conn, $sql);




if (isset($_POST['download_passwords'])) {
    // Set the filename for the downloaded CSV file
    $filename = 'teacher_passwords.csv';

    // Set the headers for the CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open a new file handle to write the CSV data
    $file = fopen('php://output', 'w');

    // Add the CSV header row
    fputcsv($file, array('Username', 'Password'));

    // Query the login table to get all teachers' usernames and decoded passwords
    $query2 = "SELECT * FROM login WHERE role = 'teacher'";
    $result = mysqli_query($conn, $query2);

    // Loop through each row in the result set and add it to the CSV file
    while ($row = mysqli_fetch_assoc($result)) {
        $password  = base64_decode($row['password']);
        fputcsv($file, array($row['username'], $password));
    }

    // Close the file handle and exit the script
    fclose($file);
    exit();
}

// (Teacher) First, check if the button is clicked
if (isset($_POST['teacher_on_off'])) {
    // Then, get the value of the clicked button
    $button_value = $_POST['teacher_on_off'];

    // Establish a database connection

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // If the "on" button is clicked, update the is_valid column to 1 for all rows where the role is "teacher"
    if ($button_value == "on") {
        $sql = "UPDATE login SET is_valid=1 WHERE role='teacher' ";
    }

    // If the "off" button is clicked, update the is_valid column to 0 for all rows where the role is "teacher"
    if ($button_value == "off") {
        $sql = "UPDATE login SET is_valid=0 WHERE role='teacher' ";
    }

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        if ($button_value == "on") {
            echo "<script>alert('Teacher Login Turned ON');</script>";
        } else if ($button_value == "off") {
            echo "<script>alert('Teacher Login Turned OFF');</script>";
        }
    } else {
        echo "Error changing modes: " . mysqli_error($conn);
    }
}

// close the database connection
mysqli_close($conn);



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
    <title>Coordinator Dashboard</title>

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

        .container-fluid .main-content {
            padding-right: 35vw;
        }

        @media screen and (min-width: 768px) and (max-width: 1500px) {
            .container-fluid .main-content {
                padding-right: 20vw;
            }
        }

        @media (max-width: 768px) {

            .container-fluid .main-content {
                padding-right: 0;
            }
        }

        .btn-logout {

            border: 0;
            border-radius: 10px;

            min-width: 110px;
            border-radius: 22px;

            color: #FFF;
            font-size: 16px;
            font-weight: 700;
        }

        .btn-reset {

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
                                        <span class=" user-email">System Admin</span>
                                    </a>
                                </div>
                            </div>
                            <!-- End User Profile-->
                        </li>


                        <!-- User Profile -->
                        <li class="sidebar-item item1 active" data-tab="tab1">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-account-multiple-plus"></i><span class="hide-menu">Add Coordinator</span>
                            </a>
                        </li>

                        <li class="sidebar-item item2" data-tab="tab2">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-account-card-details"></i><span class="hide-menu">Show Coordinator</span>
                            </a>
                        </li>

                        <li class="sidebar-item item3" data-tab="tab3">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-download"></i><span class="hide-menu">Download Password</span>
                            </a>
                        </li>

                        <li class="sidebar-item item4" data-tab="tab4">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-toggle-switch-off"></i><span class="hide-menu">Login ON/OFF</span>
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
                                <a href="logout.php"><button type="submit" name="logout" class="btn btn-logout btn-primary text-white">Logout</button></a>
                            </form>

                            <a href="reset.php" class="btn btn-reset btn-primary text-white" target="_blank">Reset Password</a>

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




                <div id="tab1" class="tab-content">
                    <h2>Add Coordinator</h2>
                    <br>

                    <div class="col-lg-6 col-md-8">
                        <form method="POST">

                            <div class="mb-3">
                                <label>Enter Email</label>
                                <input class="form-control" type="email" placeholder="example@sitpune.edu.in" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label>Enter Password</label>
                                <input class="form-control" type="password" placeholder="" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input class="form-control" type="password" placeholder="" id="confirm_password" name="confirm_password" required>
                            </div>

                            <div class="mb-3">
                                <label>Enter Branch Name</label>
                                <input class="form-control" type="text" placeholder="Branch" id="branch" name="branch" required>
                            </div>

                            <br>

                            <button class="btn btn-primary" type="submit" name="add_coordinator">Add Coordinator</button>

                        </form>
                    </div>


                </div>



                <div id="tab2" class="tab-content">
                    <h2>Active Coordinator List</h2>
                    <br>
                    <div class="row main-content">
                        <?php
                        // display the coordinators in a table
                        echo "<table style='border-collapse: collapse;' align='center'>";
                        echo "<tr><th style='padding: 10px; border: 1px ;'>Sr.No. </th><th style='padding: 10px;  ;'>Email</th><th style='padding: 10px;  ;'>Branch</th><th style='padding: 10px;  ;'>Action</th></tr>";
                        $index = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $email = $row['username'];
                            $branch = $row['branch'];

                            echo "<tr id='row-$index'><td style='padding: 10px;  ;'>$index</td><td style='padding: 10px;  ;' ondblclick='editColumn($index, \"email\", \"$email\")'>$email</td><td style='padding: 10px;  ;' ondblclick='editColumn($index, \"branch\", \"$branch\")'>$branch</td><td style='padding: 10px;  ;'><form method='POST'><input type='hidden' name='email' value='$email'><button class='btn btn-danger' type='submit' name='delete_coordinator'>Delete</button></form></td></tr>";
                            $index++;
                        }
                        echo "</table>";
                        echo "<br>";
                        ?>


                    </div>
                </div>


                <div id="tab3" class="tab-content">
                    <h2>Download Teacher Password</h2>


                    <!-- <form method="POST">
                        Download Teacher Password:
                        <button type="submit" name="download_passwords"> Download Password </button>
                    </form> -->

                    <div class=" col-lg-6 col-xlg-6">
                        <form method="POST">
                            <button class="px-0 card-link" type="submit" name="download_passwords" style="background-color: transparent;border:0; text-align: left; outline: none;">
                                <div class=" card mb-3 d-flex justify-content-center align-items-center">

                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                            <img src="./public/DownloadIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Download Teacher Password</h5>
                                                <p class="card-text" style="font-size: 15px;">Download csv file that contains passwords of every teacher
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>

                </div>

                <div id="tab4" class="tab-content">
                    <form method="POST" >
                        Turn the teacher login ON or OFF:
                        <button class="DownloadButtom" type="submit" name="teacher_on_off" value="on" style="background:green;">ON</button>
                        <button class="DownloadButtom" type="submit" name="teacher_on_off" value="off" style="background:red;">OFF</button>

                        
                    </form>
                </div>

            </div>






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

    <script>
        function editColumn(rowId, column, value) {
            var newValue = prompt("Enter the new value:", value);
            if (newValue !== null && newValue !== "") {
                // Update the cell value on the frontend
                document.getElementById('row-' + rowId).querySelector('td:nth-child(' + (getColumnIndex(column) + 1) + ')').innerText = newValue;

                // Send an AJAX request to update the value in the backend
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Handle the response if needed
                        console.log(this.responseText);
                    }
                };
                xhttp.open("POST", "update.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("rowId=" + rowId + "&column=" + column + "&value=" + encodeURIComponent(newValue));
            }
        }

        function getColumnIndex(column) {
            // Map the column name to the column index
            switch (column) {
                case "email":
                    return 1;
                case "branch":
                    return 2;
                    // Add more columns if needed
            }
            return -1;
        }
    </script>

</body>


</html>