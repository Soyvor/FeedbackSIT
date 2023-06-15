<?php
session_start();

// Check if the user is not logged in and redirect to index.php
if (!isset($_SESSION['usertype'])) {
    header('Location: index.php');
    exit;
}



// Get username from session variable
if ($_SESSION['usertype'] == "superadmin") {
    $username = $_SESSION['SuperAdmin'];
} elseif ($_SESSION['usertype'] == "coordinator") {
    $username = $_SESSION['coordinator'];
} elseif ($_SESSION['usertype'] == "student") {
    $username = $_SESSION['student'];
} elseif ($_SESSION['usertype'] == "teacher") {
    $username = $_SESSION['Teacher'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="./public/favicon.ico" type="image/x-icon">

    <title>Password Reset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @font-face {
            font-family: "BalooBhaina-Regular";
            src: url("./public/BalooBhaina-Regular.ttf");
        }

        html,
        body {
            height: 100%;
        }

        body {
            position: relative;
            margin: 0;
            background: #BB378E;


            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;

        }

        .BOX {
            position: relative;
            margin-left: auto;
            margin-right: auto;
            top: 10%;
            width: 60%;
            background: black;
            height: 70%;
            box-shadow: 0 0 2px;
            border-radius: 58px;
            padding-left: 2%;
            padding-right: 2%;
            padding-top: 2%;
            padding-bottom: 2%;

        }

        .passwordBox {
            position: absolute;
            color: white;
            width: 40%;
            right: 5%;
            top: 3%;
            height: 80%;
            text-align: center;
        }

        .button {
            position: relative;
            width: 82%;
            height: 58px;

            border: 0px;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
            border-radius: 39px;



            padding-left: 20px;
            font-family: 'Arial';
            font-style: normal;
            font-weight: 400;
            font-size: 25px;
            line-height: 46px;

            color: rgba(0, 0, 0, 0.782);
        }

        .reset {
            position: relative;
            width: 86%;
            height: 58px;

            border: 0px;
            background: black;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
            border-radius: 39px;

            border: 8px solid #BB378E;
            font-family: 'Arial';
            font-style: normal;
            font-weight: 700;
            font-size: 24px;
            line-height: 46px;

            color: white;
        }

        @media screen and (max-width:600px) {

            html,
            body {
                height: 100%;
            }

            body {
                position: relative;
                margin: 0;
                background: #BB378E;


                background-repeat: no-repeat;
                background-size: cover;
                background-attachment: fixed;

            }

            .BOX {
                position: relative;
                margin-left: auto;
                margin-right: auto;
                top: 10%;
                width: 60%;
                background: black;
                height: 70%;
                box-shadow: 0 0 2px;
                border-radius: 58px;
                text-align: center;
                justify-content: center;
                font-size: 20px;

            }

            .textInPassowrdBox {
                margin-left: 25%;
                font-size: 20px !important;

            }

            .passwordBox {
                position: relative;
                color: white;

                margin-left: 25%;

                top: 3%;
                height: 80%;
                text-align: center;

            }

            .passwordBox form {

                position: relative;
                margin: auto;
                width: 150px;
                height: 90%;

            }

            .button {

                position: relative;

                height: 20px;


                border: 0px;
                background: white;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
                border-radius: 39px;




                font-family: 'Arial';
                font-style: normal;
                font-weight: 400;
                font-size: 10px;
                line-height: 46px;

                color: rgba(0, 0, 0, 0.782);
            }

            .reset {
                position: relative;
                width: 86%;
                height: 58px;

                border: 0px;
                background: black;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
                border-radius: 39px;

                border: 2px solid #BB378E;
                font-family: 'Arial';
                font-style: normal;
                font-weight: 700;
                font-size: 13px;
                line-height: 46px;

                color: white;
            }

            .thumbsUp {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="BOX">
        <img style=" position:absolute;height:90%;" class="thumbsUp" src="./public/IconDesignReset1.svg"></img>
        <div class="passwordBox">
            <h2 style="position:relative;font-family: 'BalooBhaina-Regular';font-style: normal;font-weight: 400;font-size: 40px;line-height: 1;">Reset Your Password</h2>
            <form method="post" action="reset_process.php">

                <input class="button" type="password" name="current_password" placeholder="Current Password"><br>
                <br>

                <input class="button" type="password" name="new_password" placeholder="New Password"><br>

                <br>
                <input class="button" type="password" name="confirm_password" placeholder="Password Confirmation"><br>
                <br>
                <br>
                <input class="reset" type="submit" value="Reset Password">
            </form>
        </div>
    </div>
</body>

</html>