<?php
// Start a session
session_start();
if (!isset($_SESSION['student'])) {
    header('Location: index.php');
    exit;
 }
require_once "connection.php";

$prn = $_SESSION['prn'];
$student_name = $_SESSION['student_name'];
$acad_year = $_SESSION['acad_year'];
$branch = $_SESSION['branch'];
$class = $_SESSION['class'];
$branch_feedback = $_SESSION['branch_feedback'];
$branch_student = $_SESSION['branch_check'];
$branch_teacher = $_SESSION['branch_teacher'];
$guest_id = $_SESSION['guest_id'];
$student_email = $_SESSION['student_email'];
$guest_name = $_SESSION['guest_name'];
$guest_date = $_SESSION['guest_date'];
$guest_topic = $_SESSION['guest_topic'];
echo "$branch_feedback";
echo "$branch_teacher";
echo "$branch_student"; 

if (isset($_POST["submit"])) {
    // Retrieve student information

            

            $values = array(
                $_POST['feedback'][$guest_id][1],
                $_POST['feedback'][$guest_id][2],
                $_POST['feedback'][$guest_id][3],
                $_POST['feedback'][$guest_id][4],
                $_POST['feedback'][$guest_id][5],
                $_POST['feedback'][$guest_id][6],
                $_POST['feedback'][$guest_id][7],
                $_POST['feedback'][$guest_id][8],
                $_POST['feedback'][$guest_id][9]
            );
            
            $average = array_sum($values) / count($values);




            // Insert feedback data into database
            $query = "INSERT INTO guest_feedback (guest_id, student_email, guest_name, guest_topic, guest_date, avg, q1, q2, q3, q4, q5, q6, q7, q8, q9, is_submitted) 
            VALUES ('$guest_id', '$student_email', '$guest_name','$guest_topic','$guest_date','$average', '{$_POST['feedback'][$guest_id][1]}', '{$_POST['feedback'][$guest_id][2]}', '{$_POST['feedback'][$guest_id][3]}', '{$_POST['feedback'][$guest_id][4]}', '{$_POST['feedback'][$guest_id][5]}', '{$_POST['feedback'][$guest_id][6]}', '{$_POST['feedback'][$guest_id][7]}', '{$_POST['feedback'][$guest_id][8]}', '{$_POST['feedback'][$guest_id][9]}',1)";

            $result2 = mysqli_query($conn, $query);

            if (!$result2) {
                die("Error inserting data into feedback_report table: " . mysqli_error($conn));}

                $query = "UPDATE guest_student
                SET is_valid = 0
                WHERE guest_id = $guest_id AND student_email = '$student_email'";
      $result3 = mysqli_query($conn, $query);
      
    //         }
    //     }
    // }
    //  else {
    //     echo "No results found.";
    // }
    // if((isset($_POST['remark'][$prn])) && !empty($_POST['remark'][$prn])){
        
    // $query_remark = "INSERT INTO remark (name, prn, year_branch_class, remark) VALUES ('$student_name', '$prn', '$class_batch','{$_POST['remark'][$prn]}')";
    // $result3 = mysqli_query($conn, $query_remark);
    // if (!$result3) {
    //     die("Error inserting data into remark table: " . mysqli_error($conn));
    // }

}


// Redirect the user to a new page to avoid resubmission on refresh
    header("Location: feedback_confirmation.php");
    exit();
?>