<?php
require('connection.php');
require('PHPMailer-master/src/PHPMailer.php');
require('PHPMailer-master/src/Exception.php');
require('PHPMailer-master/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $resetToken)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sysfeedback23@gmail.com';
        $mail->Password   = 'lnwuryzxqidcmbmx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('sysfeedback23@gmail.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
        $mail->Body    = "We received a request from you to reset your password!<br>Click the link below to reset your password:<br>
        <a href='http://localhost/sys-1/forgot-password-process.php?email=$email&reset_token=$resetToken'>Reset Password</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $resetToken = bin2hex(random_bytes(16));
        $sql = "UPDATE login SET resettoken = '$resetToken' WHERE email = '$email'";

        if ($conn->query($sql) === TRUE && sendMail($email, $resetToken)) {
            echo "<script>
                alert('Password reset link sent to your email.');
                window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>
                alert('Failed to send reset link. Please try again later.');
                window.location.href = 'forgot-password.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Email Address Not Found.');
            window.location.href = 'forgot-password.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" name="submit" value="Send Reset Link">
    </form>
</body>
</html>
