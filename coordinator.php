<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}


require_once "connection.php";

$username = $_SESSION['coordinator'];
$branch = $_SESSION['branch'];
$branch_student = $branch . '' . "_student";
$branch_teacher = $branch . '' . "_teacher";
$branch_feedback = $branch . '' . "_feedback";

$username_parts = explode("@", $username);
$username_prefix = $username_parts[0];
$text = strtolower($username_prefix); // convert all characters to lowercase
$text_final = ucfirst($text);

$query = "SELECT * FROM login WHERE username = '$username'";
$result = mysqli_query($conn, $query);




if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// extract branch value from resulting row
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $branch = $row['branch'];
    $branch1 = $branch;
} else {
    // handle case where no rows are returned
    echo "No rows found for username $username";
}


?>


<?php
if (isset($_POST['submit1'])) {
    if (!isset($_FILES['csvfile_student_data']) || empty($_FILES['csvfile_student_data']['name'])) {
        echo "<script>alert('File Not Selected! Please Upload A File');</script>";
    } 
    else {
        // Check if the uploaded file is a CSV file
        $file_name = $_FILES['csvfile_student_data']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($file_ext !== 'csv') {
            echo "<script>alert('Invalid File Type! Please upload a CSV file.');</script>";
        } else {
    
        // Open uploaded file
        $file = fopen($_FILES['csvfile_student_data']['tmp_name'], 'r');


        // Skip first line (header)
        fgetcsv($file);

        // Connect to database
        

        // Loop through each row of the CSV file
        while ($row = fgetcsv($file)) {
            // Sanitize and validate each field
            $prn = sanitizeAndValidatePrn($row[0]);
            $name = sanitizeAndValidateName($row[1]);
            $email = sanitizeAndValidateEmail($row[2]);
            $open = sanitizeAndValidateVarchar($row[3]);
            $general = sanitizeAndValidateVarchar($row[4]);
            $acad_year = sanitizeAndValidateAcadYear($row[5]);
            $branch = sanitizeAndValidateBranch($row[6]);
            $class = sanitizeAndValidateClass($row[7]);
            $sem = sanitizeAndValidateSemester($row[8]);
            $crnt_year = date('Y');

            // Check if the username already exists in login
            $sql = "SELECT COUNT(*) as count FROM login WHERE username = '$prn'";
            $result = mysqli_query($conn, $sql);
            $row_count = mysqli_fetch_assoc($result);
            $count = $row_count['count'];

            if ($name === false || $email === false || $open === false || $general === false || $acad_year === false || $branch === false || $class === false || $sem === false) {

                continue; // Skip the row if any of the fields are invalid
            }
            // If the username does not exist, insert the row into login
            if ($count == 0) {
                // Generate a random 4-digit alphanumeric password
                $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);
                // Encode the password in base64
                $password = base64_encode($password);
                
                // Insert data into login table
                $sql = "INSERT INTO login (username, email, password, role, acad_year, branch , class,semester, crnt_year ,is_valid)
                 VALUES ('$prn','$email', '$password','student','$acad_year','$branch','$class','$sem','$crnt_year','1')";
                mysqli_query($conn, $sql);

                // // Insert data into student_data table
                $sql = "INSERT INTO $branch_student (prn, name, email, open, general,  acad_year, branch , class, semester, crnt_year, is_valid) 
                VALUES ('$prn','$name','$email','$open','$general','$acad_year','$branch','$class','$sem','$crnt_year','1')";
                mysqli_query($conn, $sql);
            }
        }
        echo "<script>alert('File Uploaded successfully!');</script>";
        // Close database connection and file
        mysqli_close($conn);
        fclose($file);
    }
}
}

function sanitizeAndValidatePrn($prn)
{
    $prn = preg_replace('/[^0-9]/', '', $prn); // Remove non-digit characters
    if (strlen($prn) !== 11 || !ctype_digit($prn)) {
        echo "<script>alert('Invalid PRN: $prn');</script>";
        // Handle the error (e.g., log, display, etc.)
        return false;
    }
    else{
        return $prn;
    }
    
}

function sanitizeAndValidateName($name)
{
    $name = trim(preg_replace('/[^a-zA-Z ]/', '', $name)); // Remove non-alphabet and space characters and trim spaces
    return $name;
}

function sanitizeAndValidateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email: $email');</script>";
        // Handle the error (e.g., log, display, etc.)
        return false; // Skip the iteration if the email is invalid
    }
    else{
    return $email;
}
}

function sanitizeAndValidateVarchar($varchar)
{
    $varchar = preg_replace('/[^a-zA-Z ]/', '', $varchar); // Remove non-alphabet and space characters
    return $varchar;
}

function sanitizeAndValidateAcadYear($acad_year)
{
    $allowedYears = ['fy', 'sy', 'ty', 'fly'];
    if (!in_array(strtolower($acad_year), $allowedYears)) {
        echo "<script>alert('Invalid academic year: $acad_year');</script>";
        // Handle the error (e.g., log, display, etc.)
        return false; // Skip the iteration if the academic year is invalid
    }
    else{
    return strtolower($acad_year);}
}

function sanitizeAndValidateBranch($branch)
{
    $branch = preg_replace('/[^a-zA-Z]/', '', $branch); // Remove non-alphabet characters
    return strtolower($branch);
}

function sanitizeAndValidateClass($class)
{
    $class = preg_replace('/[^a-zA-Z0-9]/', '', $class); // Remove non-alphabet characters
    return strtolower($class);
}

function sanitizeAndValidateSemester($sem)
{
    if (!ctype_digit($sem) || $sem < 1 || $sem > 8) {
        echo "<script>alert('Invalid semester: $sem');</script>";
        // Handle the error (e.g., log, display, etc.)
        return false; // Skip the iteration if the semester is invalid
    }
    else{
    return $sem;
}}

if (isset($_POST['submit'])) {
    if (!isset($_FILES['csvfile_teacher_data']) || empty($_FILES['csvfile_teacher_data']['name'])) {
        echo "<script>alert('File Not Selected! Please Upload A File');</script>";
    } else {
        // Open uploaded file
        $file = fopen($_FILES['csvfile_teacher_data']['tmp_name'], 'r');

        // Skip first line (header)
        fgetcsv($file);

        // Loop through each row in the CSV file
        while ($row = fgetcsv($file)) {
            //Get the email from the CSV row
            $email = sanitizeAndValidateEmail($row[0]);
            $name = sanitizeAndValidateVarchar($row[1]);
            $subject = sanitizeAndValidateVarchar($row[2]);
            $acad_year = sanitizeAndValidateAcadYear($row[3]);
            $branch = sanitizeAndValidateBranch($row[4]);
            $class = sanitizeAndValidateClass($row[5]);

            // Check if the email already exists in login
            $user_sql = "SELECT COUNT(*) as count FROM login WHERE username = '$email'";
            $user_result = mysqli_query($conn, $user_sql);
            $user_row_count = mysqli_fetch_assoc($user_result);
            $user_count = $user_row_count['count'];

            // Check if the email and year_branch_class already exists in teacher_data
            $teacher_sql = "SELECT COUNT(*) as count FROM $branch_teacher WHERE email = '$email' AND acad_year = '$acad_year' AND branch = '$branch' AND class = '$class' AND subject = '$subject'";
            $teacher_result = mysqli_query($conn, $teacher_sql);
            $teacher_row_count = mysqli_fetch_assoc($teacher_result);
            $teacher_count = $teacher_row_count['count'];

            // If the email and year_branch_class already exist in both login and teacher_data, skip the current row
            if ($user_count > 0 && $teacher_count > 0) {
                continue;
            }

            // If the email does not exist in login, insert the row into login and teacher_data
            if ($user_count == 0) {
                // Insert the row into login
                // Generate a random 4-digit alphanumeric password
                $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);
                // Encode the password in base64
                $password = base64_encode($password);
                $user_insert_sql = "INSERT INTO login (username, email, password, role ,  acad_year, branch, class, crnt_year, is_valid)
                 VALUES ('$email','$email', '$password', 'teacher','','','','2023','1')";
                mysqli_query($conn, $user_insert_sql);
            }

            // If the email and year_branch_class does not exist in teacher_data, insert the row into teacher_data
            if ($teacher_count == 0) {
                $year = date('Y');
                $teacher_insert_sql = "INSERT INTO $branch_teacher (email, name, subject, acad_year, branch, class, is_valid)
                 VALUES ('$email','$name','$subject','$acad_year','$branch','$class','1')";
                mysqli_query($conn, $teacher_insert_sql);
            }
        }
        echo "<script>alert('File Uploaded successfully!');</script>";
        // Close database connection and file
        mysqli_close($conn);
        fclose($file);
    }
}





// Check if the download button is clicked
if (isset($_POST['download_csv'])) {
    // Retrieve branch from the form
    $valid_year = date('My');


    // Retrieve feedback data for the given branch
    // if($branch1=='FY'){
    //     $query = "SELECT * FROM feedback_report WHERE year_branch_class LIKE 'FY-%'";
    // }
    // else{
    //     $query = "SELECT * FROM feedback_report WHERE year_branch_class LIKE '%-$branch-%'";

    // }
    $query = "SELECT * FROM $branch_feedback";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Set the HTTP headers for a CSV download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=feedback_' . $valid_year . '_' . $branch . '.csv');


    // Add the CSV header row
    fputcsv($output, array('Name', 'PRN', 'Academic Year', 'Branch', 'Class', 'Teacher', 'Subject', 'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9', 'Average'));

    // Loop through the feedback data and add each row to the CSV file
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Process each row here
            $name = ucwords(strtolower($row['name']));
            $prn = $row['prn'];
            $acad_year = $row['acad_year'];
            $branch = $row['branch'];
            $class = $row['class'];
            $teacher = ucwords(strtolower($row['teacher']));
            $subject = ucwords(strtolower($row['subject']));
            $q1 = $row['q1'];
            $q2 = $row['q2'];
            $q3 = $row['q3'];
            $q4 = $row['q4'];
            $q5 = $row['q5'];
            $q6 = $row['q6'];
            $q7 = $row['q7'];
            $q8 = $row['q8'];
            $q9 = $row['q9'];
            $average = $row['avg'];
            // Add the row to the CSV file
            fputcsv($output, array($name, $prn, $acad_year, $branch, $class, $teacher, $subject, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $average));
        }
    }

    // Close the file pointer
    fclose($output);

    // Stop the PHP script from executing further
    exit();
}

if (isset($_POST['download_remark'])) {
    // Retrieve branch from the form


    // Retrieve student data for the given branch
    if ($branch1 == "FY") {
        $remark1 = "SELECT name, prn, year_branch_class, remark FROM remark WHERE year_branch_class LIKE 'FY-%'";
    } else {
        $remark1 = "SELECT name, prn, year_branch_class, remark FROM remark WHERE year_branch_class LIKE '%-$branch1-%'";
    }



    $result = mysqli_query($conn, $remark1);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    // Create an array to hold the data for the CSV file
    $csv_data = array();

    // Loop through the student data and add it to the CSV data array
    if ($result && mysqli_num_rows($result) > 0) {
        // Add the header row to the CSV data array
        $csv_data[] = array('Name', 'PRN', 'Year/Branch/Class', 'Remark');

        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $prn = $row['prn'];
            $year_branch_class = $row['year_branch_class'];
            $remark = $row['remark'];

            // Add the row to the CSV data array
            $csv_data[] = array($name, $prn, $year_branch_class, $remark);
        }
    }

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Set the HTTP headers for a CSV download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=remark_data.csv');

    // Loop through the CSV data array and write each row to the file pointer
    foreach ($csv_data as $row) {
        fputcsv($output, $row);
    }

    // Close the file pointer
    fclose($output);

    // Stop the PHP script from executing further
    exit();
}


// Check if the download button is clicked
if (isset($_POST['download_csv_not_submitted'])) {
    // Retrieve branch from the form
    // Retrieve feedback data for the given branch


    $query = "SELECT prn FROM $branch_student";



    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    // Create an array to hold the PRNs of students who have not submitted feedback
    $prns = array();

    // Loop through the student data and check if they have submitted feedback
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $prn = $row['prn'];

            // Check if the student has submitted feedback
            $feedback_query = "SELECT * FROM $branch_feedback WHERE prn='$prn'";
            $feedback_result = mysqli_query($conn, $feedback_query);
            if (!$feedback_result) {
                die("Error executing query: " . mysqli_error($conn));
            } else if (mysqli_num_rows($feedback_result) == 0) {
                // The student has not submitted feedback, so add their PRN to the array
                $prns[] = $prn;
            }
        }
    }

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Set the HTTP headers for a CSV download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=feedback_not_submitted.csv');

    // Add the CSV header row
    fputcsv($output, array('PRN', 'Academic Year', 'Branch', 'Class', 'Email'));

    // Loop through the list of PRNs and add each row to the CSV file
    foreach ($prns as $prn) {
        // Get the year/branch/class and email for the student
        $query = "SELECT acad_year, branch, class, email FROM $branch_student WHERE prn='$prn'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Error executing query: " . mysqli_error($conn));
        }
        $row = mysqli_fetch_assoc($result);
        $acad_year = $row['acad_year'];
        $branch = $row['branch'];
        $class = $row['class'];
        $email = $row['email'];

        // Add the row to the CSV file
        fputcsv($output, array($prn, $acad_year, $branch, $class, $email));
    }


    // Close the file pointer
    fclose($output);

    // Stop the PHP script from executing further
    exit();
}

// (Student) First, check if the button is clicked
if (isset($_POST['on_off'])) {
    // Then, get the value of the clicked button
    $button_value = $_POST['on_off'];

    // Establish a database connection

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    //old code- If the "on" button is clicked, update the is_valid column to 1 for all rows where the role is "student"
    // if ($button_value == "on") {
    //     if($branch=="FY")
    //     {
    //            $sql = "UPDATE login SET is_valid=1 WHERE role='student' AND branch='$branch'";

    //     }
    //     else{
    //         $sql = "UPDATE login SET is_valid=1 WHERE role='student' AND branch='$branch'";
    //     }

    // }

    // // old code- If the "off" button is clicked, update the is_valid column to 0 for all rows where the role is "student"
    // if ($button_value == "off") {
    //     $sql = "UPDATE login SET is_valid=0 WHERE role='student' AND branch='$branch'";
    // }




    if ($button_value == "on") {
        if ($branch1 == 'FY') {
            $sql = "UPDATE login SET is_valid=1 WHERE role='student' AND (branch='CSE' OR branch='AI' OR branch='Civil' OR branch='RNA' OR
        branch='E\\&TC' OR branch='MECH')";
        } else {
            $sql = "UPDATE login SET is_valid=1 WHERE role='student' AND branch='$branch'";
        }
    }

    // If the "off" button is clicked, update the is_valid column to 0 for all rows where the role is "student"
    if ($button_value == "off") {
        if ($branch1 == 'FY') {
            $sql = "UPDATE login SET is_valid=0 WHERE role='student' AND (branch='CSE' OR branch='AI' OR branch='Civil' OR branch='RNA' OR
        branch='E\\&TC' OR branch='MECH')";
        } else {
            $sql = "UPDATE login SET is_valid=0 WHERE role='student' AND branch='$branch'";
        }
    }

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        if ($button_value == "on") {
            echo "<script>alert('Student Login Turned ON');</script>";
        } else if ($button_value == "off") {
            echo "<script>alert('Student Login Turned OFF');</script>";
        }
    } else {
        echo "Error changing modes: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
// (Teacher) First, check if the button is clicked
// if (isset($_POST['teacher_on_off'])) {
//     // Then, get the value of the clicked button
//     $button_value = $_POST['teacher_on_off'];

//     // Establish a database connection

//     // Check if the connection was successful
//     if (!$conn) {
//         die("Connection failed: " . mysqli_connect_error());
//     }

//     // If the "on" button is clicked, update the is_valid column to 1 for all rows where the role is "teacher"
//     if ($button_value == "on") {
//         $sql = "UPDATE login SET is_valid=1 WHERE role='teacher' ";
//     }

//     // If the "off" button is clicked, update the is_valid column to 0 for all rows where the role is "teacher"
//     if ($button_value == "off") {
//         $sql = "UPDATE login SET is_valid=0 WHERE role='teacher' ";
//     }

//     // Execute the SQL query
//     if (mysqli_query($conn, $sql)) {
//         if ($button_value == "on") {
//             echo "<script>alert('Teacher Login Turned ON');</script>";
//         } else if ($button_value == "off") {
//             echo "<script>alert('Teacher Login Turned OFF');</script>";
//         }
//     } else {
//         echo "Error changing modes: " . mysqli_error($conn);
//     }

//     // Close the database connection
//     mysqli_close($conn);
// }

// if (isset($_POST['download_passwords'])) {
//     // Set the filename for the downloaded CSV file
//     $filename = 'teacher_passwords.csv';

//     // Set the headers for the CSV file
//     header('Content-Type: text/csv');
//     header('Content-Disposition: attachment; filename="'.$filename.'"');

//     // Open a new file handle to write the CSV data
//     $file = fopen('php://output', 'w');

//     // Add the CSV header row
//     fputcsv($file, array('Username', 'Password'));

//     // Query the login table to get all teachers' usernames and decoded passwords
//     $sql = "SELECT * FROM login WHERE role = 'teacher'";
//     $result = mysqli_query($conn, $sql);

//     // Loop through each row in the result set and add it to the CSV file
//     while ($row = mysqli_fetch_assoc($result)) {
//         $password  = base64_decode($row['password']);
//         fputcsv($file, array($row['username'],$password));
//     }

//     // Close the file handle and exit the script
//     fclose($file);
//     exit();
// }

?>

<!-- <!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="./public/favicon.ico" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style1.css">
    <title>Coordinator</title>
    


</head>

<body>
   
        <h2 class="welcome">
            Welcome <?php echo $text_final; ?>

        </h2>

        <div class="BOX">
            <div align="right">
                <form action="logout.php" method="POST" style="display: inline-block;">
                    <a href="logout.php"><button type="submit" name="logout" class="button_css" style="display: inline-block;">Logout</button></a>
                </form>

                <a href="reset.php"><button type="button" class="button_css" style="display: inline-block;">Reset Password</button></a>
            </div>

            <br>
            <h5 class="TextInsideBOX">
                Format CSV File for Student Data:
                <a href="student_data_format.csv" download><button class="DownloadButtom">Download CSV</button></a>

            </h5>
            <br>
            <h5>
                <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <label for="csvfile">Select CSV file for students:</label>
                    <input class="ChooseFile" type="file" name="csvfile_student_data" id="csvfile"><br>
                    <input class="UploadButton" type="submit" name="submit1" value="Upload">
                </form>
            </h5>

            <br>
            <h5>
                Format CSV File for Teacher Data:
                <a href="teacher_data_format.csv" download><button class="DownloadButtom">Download CSV</button></a>


            </h5>
            <br>
            <h5>
                <form method="POST" enctype="multipart/form-data">
                    <label for="csvfile">Select CSV file for teachers:</label>
                    <input class="ChooseFile" type="file" name="csvfile_teacher_data" id="csvfile"><br>
                    <input class="UploadButton" type="submit" name="submit" value="Upload">
                </form>
            </h5>

            <br>
            <h5>
                <form method="POST">
                    Download Feedback for you branch
                    (<?php echo $branch ?>) :
                    <button class="DownloadButtom" type="submit" name="download_csv">Download CSV</button>
                    <button class="DownloadButtom" type="submit" name="download_remark">Download Remark</button>
                </form>
            </h5>

            <br>
            <h5>
                <form method="POST">
                    Download File for students who have not submitted it
                    (<?php echo $branch ?>) :
                    <button class="DownloadButtom" type="submit" name="download_csv_not_submitted">Download CSV</button>
                </form>
            </h5>
            <br>
            <h5>
                <form method="POST">
                    Turn the student login ON or OFF:
                    <button class="DownloadButtom" type="submit" name="on_off" value="on" style="background:green;">ON</button>
                    <button class="DownloadButtom" type="submit" name="on_off" value="off" style="background:red;">OFF</button>
                </form>
            </h5>
            <br>
            <!-- <h5>
                <form method="POST">
                    Turn the teacher login ON or OFF:
                    <button class="DownloadButtom" type="submit" name="teacher_on_off" value="on" style="background:green;">ON</button>
                    <button class="DownloadButtom" type="submit" name="teacher_on_off" value="off" style="background:red;">OFF</button>
                </form>
            </h5>
            <br> -->
<!-- <h5>
                <form method="POST">
                    Download Teacher Password:
                    <button class="DownloadButtom" type="submit" name="download_passwords" >Download Password</button>
                    </form>
            </h5> -->

<!-- </div>
        <footer class="footer_new">
            <p class='F1'>Feedback | © COPYRIGHT 2023</p>
            <p class='F2'>Ideation By: Head CSE
            <p>
            <p class='F3'>Developed By: <a href='https://www.linkedin.com/in/swayam-pendgaonkar-ab4087232/' target='_blank' class='link' style='color:black'>Swayam Pendgaonkar</a><br />UI/UX: <a href='https://www.linkedin.com/in/sakshamgupta912/' target='_blank' class='link' style='color:black'>Saksham Gupta</a> ,<a href='https://www.linkedin.com/in/yajushreshtha-shukla/' target='_blank' class='link' style='color:black'>Yajushreshtha Shukla</a> </p>

        </footer>
    </div> -->



<script type="text/javascript" src="js.jquery.min.js"></script>
<script type="text/javascript" src="js.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
<!-- </body>

</html>  -->


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
    <link rel="canonical" href="https://www.wrappixel.com/templates/xtreme-admin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./Coordinator Dashboard css and js /assets/images/favicon.png">
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



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                        <span class=" user-email">Coordinator@sitpune.edu.in</span>
                                    </a>
                                </div>
                            </div>
                            <!-- End User Profile-->
                        </li>


                        <!-- User Profile -->
                        <li class="sidebar-item item1 active" data-tab="tab1">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-face"></i><span class="hide-menu">Student</span>
                            </a>
                        </li>

                        <li class="sidebar-item item2" data-tab="tab2">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-account-network"></i><span class="hide-menu">Teacher</span>
                            </a>
                        </li>

                        <li class="sidebar-item item3" data-tab="tab3">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-file"></i><span class="hide-menu">Feedback</span>
                            </a>
                        </li>

                        <li class="sidebar-item item4" data-tab="tab4">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-account-star-variant"></i><span class="hide-menu">Guest</span>
                            </a>
                        </li>

                        <li class="sidebar-item item5" data-tab="tab5">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" aria-expanded="false">
                                <i class="mdi mdi-account-key"></i><span class="hide-menu">Password</span>
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
        <div class="page-wrapper" >
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
                                <a href="logout.php"><button type="submit" name="logout" class="btn btn-primary text-white">Logout</button></a>
                            </form>

                            <a href="reset.php" class="btn btn-primary text-white" target="_blank">Reset Password</a>

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



                <!-- Content for Tab 1 -->
                <div id="tab1" class="tab-content">
                    <h2>Student Manager</h2>
                    <br>
                    <div class="row main-content" >
                        <div class=" col-lg-6 col-xlg-6">
                            <a href=" student_data_format.csv " class=" card-link">
                                <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                            <img src="./public/DownloadIcon.svg" class="img-fluid rounded-circle " width="250px" alt="Download">



                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Download</h5>
                                                <p class="card-text" style="font-size: 15px;">Download template for student data
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Column 2 -->

                        <div class=" col-lg-6 col-xlg-6">
                            <a href=" student_edit.php " class=" card-link">
                                <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                            <img src="./public/EditIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Edit Icon">



                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Edit </h5>
                                                <p class="card-text" style="font-size: 15px;">Edit Student Data
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Column 3 -->

                        <div class="col-lg-6 col-xlg-6">
                            <a href="" class="card-link" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <div class="card mb-3 d-flex justify-content-center align-items-center">
                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">
                                            <img src="./public/UploadIcon.svg" class=" img-fluid rounded-circle" width="250px" alt="Download">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Upload</h5>
                                                <p class="card-text" style="font-size: 15px;">Upload Data of Student (CSV Format Only)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="modal fade " id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                                    <div class="modal-content " style="background-color:#202E39;">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Upload Student Data </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                                        </div>
                                        <div class="modal-body">

                                            <label for="csvfile">Select CSV file for students data:</label>
                                            <br>
                                            <input class="ChooseFile btn btn-secondary" type="file" name="csvfile_student_data" id="csvfile"><br>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>


                                            <input class="UploadButton btn btn-primary" type="submit" name="submit1" value="Upload">


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- Column 4 -->
                        <div class="col-lg-6 col-xlg-6">
                            <a href="" class="card-link" data-bs-toggle="modal" data-bs-target="#Student_Login_ON_OFF_Modal">
                                <div class="card mb-3 d-flex justify-content-center align-items-center">
                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">
                                            <img src="./public/DisableIcon.svg" class=" img-fluid rounded-circle" width="250px" alt="DisableIcon">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Disable</h5>
                                                <p class="card-text" style="font-size: 15px;">Turn the student login ON or OFF</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="modal fade " id="Student_Login_ON_OFF_Modal" tabindex="-1" aria-labelledby="Student_Login_ON_OFF_ModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <form method="POST">
                                    <div class="modal-content " style="background-color:#202E39;">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Turn Student Login ON/OFF </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                                        </div>
                                        <div class="modal-body">

                                            <button class="btn btn-success" type="submit" name="on_off" value="on" style="background:green;">ON</button>
                                            <button class="btn btn-danger" type="submit" name="on_off" value="off" style="background:red;">OFF</button>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>


                <div id="tab2" class="tab-content">
                    <h2>Teacher Manager</h2>
                    <br>
                    <div class="row main-content" >

                        <!-- Column 1 -->
                        <div class=" col-lg-6 col-xlg-6">
                            <a href="teacher_data_format.csv " class=" card-link">
                                <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                            <img src="./public/DownloadIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Download</h5>
                                                <p class="card-text" style="font-size: 15px;">Download template for teacher data
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Column 2 -->

                        <div class=" col-lg-6 col-xlg-6">
                            <a href=" teacher_edit.php " class=" card-link">
                                <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                            <img src="./public/EditIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Edit Icon">



                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Edit </h5>
                                                <p class="card-text" style="font-size: 15px;">Edit Teacher Data
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Column 3 -->

                        <div class="col-lg-6 col-xlg-6">
                            <a href="" class="card-link" data-bs-toggle="modal" data-bs-target="#uploadTeacherModal">
                                <div class="card mb-3 d-flex justify-content-center align-items-center">
                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">
                                            <img src="./public/UploadIcon.svg" class=" img-fluid rounded-circle" width="250px" alt="Download">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Upload</h5>
                                                <p class="card-text" style="font-size: 15px;">Upload Data of Teacher (CSV Format Only)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="modal fade " id="uploadTeacherModal" tabindex="-1" aria-labelledby="uploadTeacherModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-content " style="background-color:#202E39;">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Upload Teacher Data </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                                        </div>
                                        <div class="modal-body">

                                            <label for="csvfile">Select CSV file for teacher data:</label>
                                            <br>
                                            <input class="ChooseFile btn btn-secondary" type="file" name="csvfile_teacher_data" id="csvfile"><br>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>

                                            <input class="UploadButton btn btn-primary" type="submit" name="submit" value="Upload">


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- Column 4 -->
                        <div class="col-lg-6 col-xlg-6">
                            <a href="#" class="card-link" data-bs-toggle="modal" data-bs-target="#Teacher_Login_ON_OFF_Modal">
                                <div class="card mb-3 d-flex justify-content-center align-items-center">
                                    <div class="row g-0">
                                        <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">
                                            <img src="./public/DisableIcon.svg" class=" img-fluid rounded-circle" width="250px" alt="DisableIcon">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title" style="font-size: 25px;">Disable</h5>
                                                <p class="card-text" style="font-size: 15px;">Turn the teacher login ON or OFF</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="modal fade " id="Teacher_Login_ON_OFF_Modal" tabindex="-1" aria-labelledby="Teacher_Login_ON_OFF_ModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <form method="POST">
                                    <div class="modal-content " style="background-color:#202E39;">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Turn Teacher Login ON/OFF </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                                        </div>
                                        <div class="modal-body">

                                            <button class="btn btn-success" type="submit" name="teacher_on_off" value="on" style="background:green;">ON</button>
                                            <button class="btn btn-danger" type="submit" name="teacher_on_off" value="off" style="background:red;">OFF</button>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Content for Tab 3 -->
                <div id="tab3" class="tab-content">
                    <h2>Feedback Manager</h2><br>
                    <div class="row main-content" >

                        <!-- Column 1 -->
                        <div class=" col-lg-6 col-xlg-6">
                            <form method="POST">
                                <button class="px-0 card-link" type="submit" name="download_csv_not_submitted" style="background-color: transparent;border:0; text-align: left; outline: none;">
                                    <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                        <div class="row g-0">
                                            <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                                <img src="./public/DownloadIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="font-size: 25px;">Feedback Not Submitted</h5>
                                                    <p class="card-text" style="font-size: 15px;">Download File for students who have not submitted Feedback
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <!-- Column 2 -->
                        <div class=" col-lg-6 col-xlg-6">
                            <form method="POST">
                                <button class="px-0 card-link" type="submit" name="download_csv" style="background-color: transparent;border:0; text-align: left; outline: none;">
                                    <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                        <div class="row g-0">
                                            <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                                <img src="./public/DownloadIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="font-size: 25px;">Feedback Download</h5>
                                                    <p class="card-text" style="font-size: 15px;"> Download Feedback File for your branch (witout remark)
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>

                        <!-- Column 3 -->
                        <div class=" col-lg-6 col-xlg-6">
                            <form method="POST">
                                <button class="px-0 card-link" type="submit" name="download_remark" style="background-color: transparent;border:0; text-align: left; outline: none;">
                                    <div class=" card mb-3 d-flex justify-content-center align-items-center" >

                                        <div class="row g-0">
                                            <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                                <img src="./public/DownloadIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Download">



                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="font-size: 25px;">Remark Download</h5>
                                                    <p class="card-text" style="font-size: 15px;"> Download Remark File for your branch
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>














                    </div>
                </div>
                <!-- Content for Tab  4-->
                <div id="tab4" class="tab-content">
                    <h2>Guest Manager</h2><br>

                    <!-- Content for Tab  5-->
                </div>
                <div id="tab5" class="tab-content">
                    <h2>Password Manager</h2><br>

                    <div class=" col-lg-6 col-xlg-6">
                        <a href=" send-mail.php " class=" card-link">
                            <div class=" card mb-3 d-flex justify-content-center align-items-center" style="max-width:400px">

                                <div class="row g-0 d-flex justify-content-center align-items-center">
                                    <div class="col-md-4 d-flex align-items-center justify-content-center px-1 py-1">

                                        <img src="./public/SendIcon.svg" class=" img-fluid rounded-circle " width="250px" alt="Edit Icon">



                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title" style="font-size: 25px;">Send Password </h5>
                                            <p class="card-text" style="font-size: 15px;">Send Password to teachers and and students of your branch
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>








                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>


</body>

</html>