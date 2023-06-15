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
$class_batch = $_SESSION['class_batch'];




if (isset($_POST["submit"])) {
    // Retrieve student information
    $query = "SELECT * FROM student_data WHERE prn='$prn'";
    $result1 = mysqli_query($conn, $query);
    $row1 = mysqli_fetch_assoc($result1);
    $student_name = $row1['name'];
    $open_elective = $row1['open_elective'];
    $specialization = $row1['specialization'];
    $class_batch = $row1['year_branch_class'];
    $email = $row1['student_email'];
    $ph_no = $row1['student_mobile'];

    $subjects_arr = preg_split('/\s*,\s*/', "$open_elective,$specialization");
    
    // Build the query
    $subject_condition = "subject IN ('" . implode("', '", $subjects_arr) . "')";
    $year_branch_class_condition = "year_branch_class='$class_batch' AND is_valid='1'";
    $year_branch_class_without_batch = substr($class_batch, 0, -1);
    $year_branch_class_without_batch_condition = "year_branch_class='$year_branch_class_without_batch' AND is_valid='1'";

    // Construct the final query with both conditions
    $query = "SELECT * FROM teacher_data WHERE ($year_branch_class_condition) OR ($year_branch_class_without_batch_condition AND $subject_condition)";
    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    // Loop through each teacher
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Process each row here
            $teacher_name = $row['name'];
            $teacher_subject = $row['subject'];
            $valid_year=date('Y');

            $values = array(
                $_POST['feedback'][$teacher_name][$teacher_subject][1],
                $_POST['feedback'][$teacher_name][$teacher_subject][2],
                $_POST['feedback'][$teacher_name][$teacher_subject][3],
                $_POST['feedback'][$teacher_name][$teacher_subject][4],
                $_POST['feedback'][$teacher_name][$teacher_subject][5],
                $_POST['feedback'][$teacher_name][$teacher_subject][6],
                $_POST['feedback'][$teacher_name][$teacher_subject][7],
                $_POST['feedback'][$teacher_name][$teacher_subject][8],
                $_POST['feedback'][$teacher_name][$teacher_subject][9]
            );
            
            $average = array_sum($values) / count($values);




            // Insert feedback data into database
            $query = "INSERT INTO feedback_report (name, prn, year_branch_class, teacher, subject, q1, q2, q3, q4, q5, q6, q7, q8, q9, avg ,valid_year, is_submitted) VALUES ('$student_name', '$prn', '$class_batch','$teacher_name', '$teacher_subject', '{$_POST['feedback'][$teacher_name][$teacher_subject][1]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][2]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][3]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][4]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][5]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][6]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][7]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][8]}', '{$_POST['feedback'][$teacher_name][$teacher_subject][9]}','$average','$valid_year','1')";

            $result2 = mysqli_query($conn, $query);

            if (!$result2) {
                die("Error inserting data into feedback_report table: " . mysqli_error($conn));
            }
        }
    } else {
        echo "No results found.";
    }
    if((isset($_POST['remark'][$prn])) && !empty($_POST['remark'][$prn])){
        
    $query_remark = "INSERT INTO remark (name, prn, year_branch_class, remark) VALUES ('$student_name', '$prn', '$class_batch','{$_POST['remark'][$prn]}')";
    $result3 = mysqli_query($conn, $query_remark);
    if (!$result3) {
        die("Error inserting data into remark table: " . mysqli_error($conn));
    }

}
}

// Redirect the user to a new page to avoid resubmission on refresh
    header("Location: feedback_confirmation.php");
    exit();
?>