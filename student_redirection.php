<?php
session_start();
if (!isset($_SESSION['student'])) {
    header('Location: index.php');
    exit;
}

require_once "connection.php";
$username = $_SESSION['student']; //gives prn

$branch = $_SESSION['branch_student'];
$branch_check = $branch . '' . "_student";
$branch_teacher = $branch . '' . "_teacher";
$branch_feedback = $branch . '' . "_feedback";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-primary {
            background-color: #007bff;
            color: #fff;
        }

        .button-secondary {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button class="button button-primary" onclick="location.href='student.php'">Go to Student</button>
        <button class="button button-secondary" onclick="location.href='student_guest.php'">Go to Student Guest</button>
    </div>
</body>
</html>
