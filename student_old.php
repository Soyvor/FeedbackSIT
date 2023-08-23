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
echo "$branch_check";
echo "$username";

if ($branch_check == "FY") {
	$query = "SELECT * FROM fy_student WHERE prn='$username'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$name = ucwords($row["name"]);
			}
		} else {
			$name = 'Please Contact Coordinator!';
		}
	} else {
		// Query execution failed
		echo "Error: " . mysqli_error($conn);
	}
} else {
	$query = "SELECT * FROM $branch_check WHERE prn='$username'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$name = ucwords($row["name"]);
			}
		} else {
			$name = 'Please Contact Coordinator!';
		}
	} else {
		// Query execution failed
		echo "Error: " . mysqli_error($conn);
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<link rel="icon" href="./public/favicon.ico" type="image/x-icon">

	<title>Student Feedback</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style1.css">
	<link rel="stylesheet" type="text/css" href="css/style2.css">
	<style>
		html,
		body {
			height: 100%;
		}

		body {
			position: relative;
			margin: 0;
			background: linear-gradient(180deg, rgba(71, 113, 162, 0.95) 0%, rgba(120, 72, 255, 0.21) 50%);

			background-repeat: no-repeat;
			background-size: cover;
			background-attachment: fixed;

		}
	</style>
</head>

<body>


	<div class="row">

	</div>

	<div class="com-md-6">
		<div class="jumbotron">
			<div class="containers">
				<div class="svg-desgin-student">
					<img src="./public/IconDesignStudent.svg" alt="">
				</div>
				<div class="circle1"><img src="./public/EllipseStudent.1.svg" alt=""> </div>
				<div class="circle2"><img src="./public/EllipseStudent.2.svg" alt=""> </div>
				<div class="circle3"><img src="./public/EllipseStudent.3.svg" alt=""> </div>

				<table align="center" style="vertical-align: top; background-color:#ffffffa9; border: 0px solid black;border-radius: 10px;" cellspacing=0 cellpadding=0 width=60%>
					<form method="post" action="submit_feedback.php" style="background-color:#FFFFFF; font-family: Arial, Helvetica, sans-serif;">
						<tr>
							<td style="padding: 10px;">
								<div align="right">


									<a href="logout.php"><button type="button" class="button_css">Logout</button></a>
									<a href="reset.php"><button type="button" class="button_css">Reset Password</button></a>

								</div>
							</td>
						</tr>
						<tr>
							<td>
								<h2 class="text-center" style=" font-family: Arial, Helvetica, sans-serif">

									Welcome

								</h2>
							</td>
						</tr>
						<tr>
							<td>
								<h3 class="text-center" style=" font-family: Arial, Helvetica, sans-serif">
									<?php echo $name; ?>
								</h3>
							</td>
						</tr>
						<br>


						<?php
						$query = "SELECT * FROM $branch_feedback WHERE prn='$username'";
						$result = mysqli_query($conn, $query);
						$row = mysqli_fetch_assoc($result);


						if (mysqli_num_rows($result) == 0) {
							// Retrieve student information
							$query = "SELECT * FROM $branch_check WHERE prn='$username'";
							$result = mysqli_query($conn, $query);
							$row = mysqli_fetch_assoc($result);
							$student_name = $row['name'];
							echo "$student_name";
							$specialization = $row['open'];
							$general = $row['general'];
							$acad_year = $row["acad_year"];
							$branch = $row["branch"];
							$class = $row["class"];
							echo " ";
							echo "$general";
							echo "$specialization";


							//$query_spec = "SELECT * FROM specialization WHERE course_name ='$specialization'";

							// // $subjects_arr = array_merge($open_elective_arr, $specialization_arr);

							// // // Build the query
							// // $subject_condition = "subject IN ('" . implode("', '", $subjects_arr) . "')";
							// // $year_branch_class_condition = "year_branch_class='$class_batch' AND is_valid='1'";
							// // $year_branch_class_without_batch = substr($class_batch, 0, -1);
							// // $year_branch_class_without_batch_condition = "year_branch_class='$year_branch_class_without_batch' AND is_valid='1'";

							// // Construct the final query with both conditions
							//$query = "SELECT * FROM $branch_teacher WHERE (acad_year = '$acad_year' AND branch = '$branch' AND class = '$class') OR subject = '$general'";



							$query = "SELECT name, subject
							FROM specialization
							WHERE course_name = '$specialization'
							UNION
							SELECT name, subject
							FROM $branch_teacher
							WHERE (acad_year = '$acad_year' AND branch = '$branch' AND class = '$class') OR subject = '$general'";
				  

							$result = mysqli_query($conn, $query);

							// Display feedback form for each teacher
							while ($row = mysqli_fetch_assoc($result)) {
								$teacher_name = $row['name'];
								$teacher_subject = $row['subject'];

								// Retrieve questions from question_data table
								$query = "SELECT * FROM question";
								$question_result = mysqli_query($conn, $query);
								$question_number = 1;




								// Display teacher and subject
								echo "<tr><td style='padding: 10px; color:black; opacity:1;  font-size:18px;  font-family: Arial, Helvetica, sans-serif;' bgcolor='#dcdfe8'  align='center'>Faculty Name: " . "<b>" . $teacher_name . " ( " . $teacher_subject . " ) </b></td></tr>";
								echo "<tr><td><br></td></tr>";



								// echo "<h3>Teacher: $teacher_name</h3>";
								// echo "<h4>Subject: $teacher_subject</h4>";

								// Display questions and radio buttons
								while ($question_row = mysqli_fetch_assoc($question_result)) {
									$question = $question_row['questions'];
									// echo "<p>Q$question_number. $question</p>";
									echo "<tr><td style='padding: 10px;  font-family: Arial, Helvetica, sans-serif'><b>" . "Q" . "" . $question . "</b></td></tr>";
									echo "<tr><td style='padding: 10px;  font-family: Arial, Helvetica, sans-serif'>";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='1' required> 1  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='2' required> 2  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='3' required> 3  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='4' required> 4  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='5' required> 5  <br></td></tr>";
									$question_number++;
								}
							}
							echo "<tr><td></td></tr>";
							echo "<tr><td><hr></td></tr>";
							echo "<tr style><td style='padding: 10px;  font-family: Arial, Helvetica, sans-serif'>Any Feedback: <input type='text' name='remark[$username]' size='35' ></td></tr>";
							$_SESSION['prn'] = $username;
							$_SESSION['student_name'] = $student_name;
							$_SESSION['acad_year'] = $acad_year;
							$_SESSION['branch'] = $branch;
							$_SESSION['class'] = $class;
							$_SESSION['branch_feedback']=$branch_feedback;
							$_SESSION['branch_check']=$branch_check;
							$_SESSION['branch_teacher']=$branch_teacher;
							$try = 0;
							// Display submit button
							echo "<tr><td align='center'><br><input type='submit' name='submit' value='Submit Feedback'></td></tr>";
							echo "</form>";
							echo "<tr><td><br></td></tr>";
						} else {
							$try = 1;
							echo "<tr><td align='center' style='padding: 10px;  font-family: Arial, Helvetica, sans-serif; font-size:25px'>You have already submitted the form!</td></tr>";
						}


						echo "</table>";







						echo "</div>";
						if ($try == 0) {
							
						}
						?>
			</div>
		</div>

	</div>

	<script type="text/javascript" src="js.jquery.min.js"></script>
	<script type="text/javascript" src="js.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>


</body>

</html>