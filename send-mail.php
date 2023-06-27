<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}

require "connection.php";

require('PHPMailer-master/src/PHPMailer.php');
require('PHPMailer-master/src/Exception.php');
require('PHPMailer-master/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $role = $_POST['role'];

    // Fetch emails and passwords from the database based on role
    $query = "SELECT email, password FROM login WHERE role='$role'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'sysfeedback23@gmail.com';
                $mail->Password   = 'lnwuryzxqidcmbmx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Email settings
                $mail->setFrom('sysfeedback23@gmail.com', 'Feedback'); // Set your email and name
                $mail->isHTML(true);
                $mail->Subject = 'Password Reminder';

                while ($row = mysqli_fetch_assoc($result)) {
                    $email = $row['email'];
                    $password = $row['password'];
                    $password = base64_decode($password);
                    // Email content
                    $mail->addAddress($email);
                    $mail->Body = "Your password is: $password";

                    $mail->send();

                    // Clear recipients for the next email
                    $mail->clearAddresses();
                }

                mysqli_close($conn);

                echo "Emails sent successfully.";
            } catch (Exception $e) {
                echo "Failed to send emails. Error: " . $mail->ErrorInfo;
            }
        } else {
            echo "No records found in the database";
        }
    } else {
        echo "Query execution failed: " . mysqli_error($conn);
    }

    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Send Emails</title>


    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

    <!-- Include Footer CSS -->
    <link rel="stylesheet" href="./css/footer_style.css">



    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
    </style>

</head>

<body>


    <div class="page-breadcrumb py-2" style=" background: #212f3e;;
            box-shadow: 0px 4px 66px 0px rgba(0, 0, 0, 0.15);">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-3">
                <a href="coordinator.php" class="btn btn-lg text-center"><span><i class="fa fa-arrow-left fa-1.5x"></i></span> Go Back to Dashboard</a>

            </div>

            <div class="col-3 text-center">

                <h4 class="page-title op-5" style="color:white ;font-size:25px">Password Manager</h4>
            </div>
            <div class="col-3">
                <div class="text-end upgrade-btn">

                    <form action="logout.php" method="POST" style="display: inline;">
                        <a href="logout.php"><button type="submit" name="logout" class="btn btn-primary text-white">Logout</button></a>
                    </form>

                    <a href="reset.php" class="btn btn-primary text-white" target="_blank">Reset Password</a>

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-5" style="background-color:#17212C;color: white; min-height:calc(100vh - 149px)">

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color:#202E39;border-radius:1rem">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                    </div>
                    <div class="modal-body">
                        Emails sent successfully.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background-color:#202E39;border-radius:1rem">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                    </div>
                    <div class="modal-body">
                        Failed to send emails.
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="background-color: transparent; border: none; box-shadow: none;">
                    <div class="modal-body text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row main-content">

            <!-- Column 1 -->
            <div class=" col-lg-6 col-xlg-6">

                <button class="px-0 card-link" type="button" onclick="sendEmails('student')" style="background-color: transparent;border:0; text-align: left; outline: none;">
                    <div class=" card mb-3 d-flex justify-content-center align-items-center">

                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                <img src="./public/SendIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title" style="font-size: 25px;">Send to Students</h5>
                                    <p class="card-text" style="font-size: 15px;">Send Password to all the students of your branch via Mail
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </button>

            </div>


            <!-- Column 2 -->
            <div class=" col-lg-6 col-xlg-6">

                <button class="px-0 card-link" type="button" onclick="sendEmails('teacher')" style="background-color: transparent;border:0; text-align: left; outline: none;">
                    <div class=" card mb-3 d-flex justify-content-center align-items-center">

                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                <img src="./public/SendIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title" style="font-size: 25px;">Send to Teachers</h5>
                                    <p class="card-text" style="font-size: 15px;">Send Password to all the teachers of your branch via Mail
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </button>

            </div>

        </div>
    </div>
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

    <script>
        function sendEmails(role) {
            // Show the loading modal
            $('#loadingModal').modal('show');

            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'role=' + role
                })
                .then(response => response.text())
                .then(data => {
                    // Hide the loading modal
                    $('#loadingModal').modal('hide');

                    // Show the success or error modal based on the response
                    if (data === 'Emails sent successfully.') {
                        $('#successModal').modal('show');
                    } else {
                        // $('#loadingModal').modal('hide');
                        $('#errorModal').modal('show');
                    }
                })
                .catch(error => {
                    // Hide the loading modal
                    $('#loadingModal').modal('hide');

                    // Show the error modal
                    $('#errorModal').modal('show');
                });
        }
    </script>

</body>

</html>