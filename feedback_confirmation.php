<?php
session_start();
if (!isset($_SESSION['student'])) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="./public/favicon.ico" type="image/x-icon">

    <title>Feedback Confirmation</title>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            position: relative;
            margin: 0;
            background: #A4668F;


            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;

        }

        .BOX {
            position: relative;
            background-color: aqua;
            margin-left: auto;
            margin-right: auto;
            top: 10%;
            width: 80%;
            background: white;
            height: 70%;
            box-shadow: 0 0 2px;
            border-radius: 58px;
            padding-left: 2%;
            padding-right: 2%;
            padding-top: 2%;
            padding-bottom: 2%;
        }

        .button_css {

            border-radius: 20px;
            margin-right: 10px;
            margin-top: 10px;
            border: 0px;
            background-color: #A4668F;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
            color: white;
            font-size: larger;

        }
    </style>
</head>

<body>

    <div class="BOX">
        <div align="right">
            <a href="logout.php"><button type="button" class="button_css">Logout</button></a>
        </div>
        <img src="./public/FeedbackThankYouDesign.1.svg" style="position:absolute;;width:40%"></img>
        <div class="ThankText" style="position:absolute;right:5%;font-family: 'Arial';font-style: normal;font-size: 20px;color: rgba(0, 0, 0, 0.74);top:30%;">
            <h1>Thank you<br>for submitting your feedback!</h1>
            <br>
            <p>Your feedback has been successfully recorded.</p>
        </div>

    </div>




</body>

</html>