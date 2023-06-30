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
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">

    <!-- Include Font Awesome CSS (for the icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include Footer CSS -->
    <link rel="stylesheet" href="./css/footer_style.css">
    <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

    <style>
        body {
            background: #17212C;

        }

        .content {
            height: calc(100vh - 97px);
        }



        .card {
            border-radius: 1.5rem;
            background: #273444;
            color: white;
            box-shadow: 0px 4px 59px -8px rgba(0, 0, 0, 0.25);
            width: 25rem;




        }

        .form-control {
            color: white;
        }

        .custom-input-box {
            background: #1C2631;
            box-shadow: 4px 4px 19px 2px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            border: 0;
            font-size: .75rem;
        }

        .custom-button {
            background-color: #3D8BFD;
            border-radius: 30px;
            min-width: 5.5rem;


            font-weight: 700;
            font-size: 0.75rem;

        }

        .custom-anchor {

            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 17px;
            color: #3D8BFD;
        }
    </style>
</head>

<!-- <body>
    <h2>Forgot Password</h2>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" name="submit" value="Send Reset Link">
    </form>
</body> -->

<body>
  <div class="content">
    <div class="container py-5 h-100 d-flex justify-content-center align-items-center ">
      <div class="row d-flex justify-content-center align-items-center ">
        <div class="card ">
          <div class="card-body  text-center py-5">

            <div class="container">


              <h3 class="mt-1 mb-1" style="text-align:start;font-size:1.3rem;">Forgot Password?</h3>

              <div class="d-flex justify-content-start  mb-4">
                <label class="form-check-label" for="form1Example3" style="color: rgba(255, 255, 255, 0.29);text-align:start;font-size:0.75rem;">Enter your E-mail Address </label>
              </div>

            </div>

            <form method="POST" action="">
              <div class="container  ">
                <div class="d-flex justify-content-start ml-1  ">
                  <label class="form-check-label mb-0" for="form1Example3" style="color: rgba(255, 255, 255, 0.29);text-align:start;font-size:0.75rem;"> E-mail </label>
                </div>
                <div class="form-outline mb-4  mx-auto">
                  <input  type="email" id="email" name="email" required class="form-control form-control-lg custom-input-box" placeholder="" />
                </div>
              </div>


              
              <div class="container d-flex justify-content-between align-items-center mt-5">
                <div><input type="submit" name="submit" class="btn btn-primary custom-button" value="Send Reset Link"></button></div>

              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
  </div>
  <!-- Site footer -->
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

  <!-- Include Bootstrap JavaScript (jQuery and Popper.js are required) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2.11.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
</body>


</html>