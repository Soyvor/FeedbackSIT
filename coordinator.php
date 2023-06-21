<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}


require_once "connection.php";

$username = $_SESSION['coordinator'];
$branch = $_SESSION['coordinator'];

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
    } else {
        // Open uploaded file
        $file = fopen($_FILES['csvfile_student_data']['tmp_name'], 'r');


        // Skip first line (header)
        fgetcsv($file);

        // Connect to database


        // Loop through each row of the CSV file
        while ($row = fgetcsv($file)) {
            $username = $row[0];

            // Check if the username already exists in login
            $sql = "SELECT COUNT(*) as count FROM login WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);
            $row_count = mysqli_fetch_assoc($result);
            $count = $row_count['count'];

            // If the username does not exist, insert the row into login
            if ($count == 0) {
                $year_branch_class = $row[4]; // assuming year_class_batch is the 5th column
                $parts = explode('-', $year_branch_class);
                $class = $parts[1];
                $class_batch = $parts[1] . '-' . $parts[2];



                // Insert data into login table
                $sql = "INSERT INTO login (username, password, branch, role, crnt_year ,is_valid) VALUES ('$username', 'MTIzNA==', '$class','student','2023','1')";
                mysqli_query($conn, $sql);

                // // Insert data into student_data table
                $name = mysqli_real_escape_string($conn, $row[1]);
                $sql = "INSERT INTO student_data (prn, name, open_elective, specialization, year_branch_class, c_a_y, branch_batch, student_email, student_mobile, a_y, is_valid) VALUES ('$username','$name','$row[2]','$row[3]','$year_branch_class','$parts[0]','$class_batch','$row[5]','$row[6]','$row[7]','1')";

                mysqli_query($conn, $sql);
            }
        }
        echo "<script>alert('File Uploaded successfully!');</script>";
        // Close database connection and file
        mysqli_close($conn);
        fclose($file);
    }
}




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
            $email = $row[0];
            $ybc = $row[2];
            $subject = $row[3];

            // Check if the email already exists in login
            $user_sql = "SELECT COUNT(*) as count FROM login WHERE username = '$email'";
            $user_result = mysqli_query($conn, $user_sql);
            $user_row_count = mysqli_fetch_assoc($user_result);
            $user_count = $user_row_count['count'];

            // Check if the email and year_branch_class already exists in teacher_data
            $teacher_sql = "SELECT COUNT(*) as count FROM teacher_data WHERE email = '$email' AND year_branch_class = '$ybc' AND subject = '$subject'";
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
                $user_insert_sql = "INSERT INTO login (username, password, branch, role, crnt_year, is_valid) VALUES ('$email', '$password', '-', 'teacher','2023','1')";
                mysqli_query($conn, $user_insert_sql);
            }

            // If the email and year_branch_class does not exist in teacher_data, insert the row into teacher_data
            if ($teacher_count == 0) {
                $name = $row[1];
                $subject = $row[3];
                $year = date('Y');
                $teacher_insert_sql = "INSERT INTO teacher_data (email, name, year_branch_class, subject, c_year, is_valid) VALUES ('$email','$name','$ybc','$subject','$year','1')";
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
    if($branch1=='FY'){
        $query = "SELECT * FROM feedback_report WHERE year_branch_class LIKE 'FY-%'";
    }
    else{
        $query = "SELECT * FROM feedback_report WHERE year_branch_class LIKE '%-$branch-%'";

    }
    // $query = "SELECT * FROM feedback_report WHERE year_branch_class LIKE '%-$branch-%'";
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
    fputcsv($output, array('Name', 'PRN', 'Year/Branch/Class', 'Teacher', 'Subject', 'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9','Average'));

    // Loop through the feedback data and add each row to the CSV file
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Process each row here
            $name = ucwords(strtolower($row['name']));
            $prn = $row['prn'];
            $year_branch_class = $row['year_branch_class'];
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
            $average=$row['avg'];
            // Add the row to the CSV file
            fputcsv($output, array($name, $prn, $year_branch_class, $teacher, $subject, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9,$average));
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
    if($branch1=="FY"){
        $remark1 = "SELECT name, prn, year_branch_class, remark FROM remark WHERE year_branch_class LIKE 'FY-%'";
    }
    else{
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

    if($branch1=='FY')
    {
        $query = "SELECT student_data.prn, student_data.year_branch_class, student_data.student_email
          FROM student_data
          WHERE student_data.year_branch_class LIKE 'FY-%'";
    }
    else{
        $query = "SELECT student_data.prn, student_data.year_branch_class, student_data.student_email
        FROM student_data
        WHERE student_data.year_branch_class LIKE '%-$branch-%'";
    }
  

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
            $year_branch_class = $row['year_branch_class'];

            // Check if the student has submitted feedback
            $feedback_query = "SELECT * FROM feedback_report WHERE prn='$prn'";
            $feedback_result = mysqli_query($conn, $feedback_query);
            if (!$feedback_result) {
                die("Error executing query: " . mysqli_error($conn));
            }
            if (mysqli_num_rows($feedback_result) == 0) {
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
    fputcsv($output, array('PRN', 'Year/Branch/Class', 'Email'));

    // Loop through the list of PRNs and add each row to the CSV file
    foreach ($prns as $prn) {
        // Get the year/branch/class and email for the student
        $query = "SELECT year_branch_class, student_email FROM student_data WHERE prn='$prn'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Error executing query: " . mysqli_error($conn));
        }
        $row = mysqli_fetch_assoc($result);
        $year_branch_class = $row['year_branch_class'];
        $email = $row['student_email'];

        // Add the row to the CSV file
        fputcsv($output, array($prn, $year_branch_class, $email));
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
        if($branch1=='FY'){
        $sql = "UPDATE login SET is_valid=1 WHERE role='student' AND (branch='CSE' OR branch='AI' OR branch='Civil' OR branch='RNA' OR
        branch='E\\&TC' OR branch='MECH')";
        }
        else{
            $sql = "UPDATE login SET is_valid=1 WHERE role='student' AND branch='$branch'";
        }
    }

    // If the "off" button is clicked, update the is_valid column to 0 for all rows where the role is "student"
    if ($button_value == "off") {
        if($branch1=='FY'){
        $sql = "UPDATE login SET is_valid=0 WHERE role='student' AND (branch='CSE' OR branch='AI' OR branch='Civil' OR branch='RNA' OR
        branch='E\\&TC' OR branch='MECH')";
        }
        else{
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
<html>
<head>
  <title>Coordinator</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.container {
  display: flex;
  height: 100vh;
}

.sidebar {
  width: 20%;
  background-color: #f2f2f2;
}

.main-content {
  width: 80%;
  background-color: #ffffff;
}

ul {
  list-style: none;
}

li {
  padding: 10px;
}
.tab-content:not(#tab1) {
      display: none;
    }

  </style>
</head>
<body>
<div align="right">
                <form action="logout.php" method="POST" style="display: inline-block;">
                    <a href="logout.php"><button type="submit" name="logout" class="button_css" style="display: inline-block;">Logout</button></a>
                </form>

                <a href="reset.php"><button type="button" class="button_css" style="display: inline-block;">Reset Password</button></a>
            </div>
  <div class="container">
    <div class="sidebar">
      <!-- Navigation Bar -->
      <ul>
        <li><a href="#tab1">Student</a></li>
        <li><a href="#tab2">Teacher</a></li>
        <li><a href="#tab3">Feedback</a></li>
        <li><a href="#tab4">Guests</a></li>
        <li><a href="#tab5">Passwords</a></li>
      </ul>
    </div>
    <div class="main-content">
      <!-- Content for Tab 1 -->
      <div id="tab1" class="tab-content">
        
        <h2>Student Manager</h2>
        <br>
        Format CSV File for Teacher Data:
        <a href="student_data_format.csv" download><button class="DownloadButtom">Download CSV</button></a>
        <br><br>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <label for="csvfile">Select CSV file for students:</label>
                    <input class="ChooseFile" type="file" name="csvfile_student_data" id="csvfile"><br>
                    <input class="UploadButton" type="submit" name="submit1" value="Upload">
        </form>
        <br>
        <a href="student_edit.php"><button class="">Edit Students</button></a>
        <br>
        <br>
        <form method="POST">
                    Turn the student login ON or OFF:
                    <button class="DownloadButtom" type="submit" name="on_off" value="on" style="background:green;">ON</button>
                    <button class="DownloadButtom" type="submit" name="on_off" value="off" style="background:red;">OFF</button>
        </form>
      </div>
      
      <!-- Content for Tab 2 -->
      <div id="tab2" class="tab-content">
        <h2>Teacher Manager</h2>
        <br>
        Format CSV File for Teacher Data:
                <a href="teacher_data_format.csv" download><button class="DownloadButtom">Download CSV</button></a>
                <br><br>
                <form method="POST" enctype="multipart/form-data">
                    <label for="csvfile">Select CSV file for teachers:</label>
                    <input class="ChooseFile" type="file" name="csvfile_teacher_data" id="csvfile"><br>
                    <input class="UploadButton" type="submit" name="submit" value="Upload">
                </form><br>
        <a href="teacher_edit.php"><button class="">Edit Teachers</button></a>
       <br><br>
       <form method="POST">
                    Turn the teacher login ON or OFF:
                    <button class="DownloadButtom" type="submit" name="teacher_on_off" value="on" style="background:green;">ON</button>
                    <button class="DownloadButtom" type="submit" name="teacher_on_off" value="off" style="background:red;">OFF</button>
                </form>
      </div>
      
      <!-- Content for Tab 3 -->
      <div id="tab3" class="tab-content">
        <h2>Feedback Manager</h2><br>
        <form method="POST">
                    Download Feedback for you branch
                    (<?php echo $branch ?>) :
                    <button class="DownloadButtom" type="submit" name="download_csv">Download CSV</button>
                    <button class="DownloadButtom" type="submit" name="download_remark">Download Remark</button>
                </form>
                <br>
                <form method="POST">
                    Download File for students who have not submitted it
                    (<?php echo $branch ?>) :
                    <button class="DownloadButtom" type="submit" name="download_csv_not_submitted">Download CSV</button>
                </form>
        
      </div>
      <div id="tab4" class="tab-content">
        <h2>Guest Manager</h2><br>
        
        
      </div>
      <div id="tab5" class="tab-content">
        <h2>Password Manager</h2><br>
        Click here to take action on passwords: <a href="send-mail.php"><button>Password Manager</button></a>
      </div>

    </div>
  </div>

  <script>
 document.addEventListener("DOMContentLoaded", function() {
  const tabs = document.querySelectorAll(".sidebar ul li a");
  const mainContent = document.querySelector(".main-content");

  tabs.forEach(function(tab) {
    tab.addEventListener("click", function(e) {
      e.preventDefault();

      // Remove active class from all tabs
      tabs.forEach(function(tab) {
        tab.classList.remove("active");
      });

      // Add active class to the clicked tab
      this.classList.add("active");

      // Fetch and display the content for the selected tab
      const tabId = this.getAttribute("href").substring(1);
      fetchContent(tabId);
    });
  });

  function fetchContent(tabId) {
    // Hide all tab content divs
    const tabContents = document.querySelectorAll(".tab-content");
    tabContents.forEach(function(tabContent) {
      tabContent.style.display = "none";
    });

    // Show the selected tab content
    const selectedTabContent = document.getElementById(tabId);
    selectedTabContent.style.display = "block";
  }
});


  </script>
</body>
</html>
