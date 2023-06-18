<?php

require('PHPMailer-master/src/PHPMailer.php');
require('PHPMailer-master/src/Exception.php');
require('PHPMailer-master/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "sit_feedback");
    if (!$conn) {
        die("Connection unsuccessful: " . mysqli_connect_error());
    }

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal">Send Emails to Students</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teacherConfirmModal">Send Email to Teacher</button>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to send emails to all students?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="sendEmails('student')">Yes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Confirm Modal -->
    <div class="modal fade" id="teacherConfirmModal" tabindex="-1" aria-labelledby="teacherConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherConfirmModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to send an email to the teacher?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="sendEmails('teacher')">Yes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sendEmails(role) {
            var confirmModal;
            if (role === 'student') {
                confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            } else if (role === 'teacher') {
                confirmModal = bootstrap.Modal.getInstance(document.getElementById('teacherConfirmModal'));
            }
            confirmModal.hide();
            var sendingModal = new bootstrap.Modal(document.getElementById('sendingModal'));
            sendingModal.show();

            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'role=' + role
                })
                .then(response => response.text())
                .then(data => {
                    sendingModal.hide();
                    var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                    resultModal.show();
                    document.getElementById('resultContent').innerHTML = data;
                })
                .catch(error => {
                    sendingModal.hide();
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                })
        }
    </script>

    <!-- Sending Modal -->
    <div class="modal fade" id="sendingModal" tabindex="-1" aria-labelledby="sendingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Sending Emails...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Emails Sent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="resultContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Failed to send emails</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
