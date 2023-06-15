<?php
session_start();
require_once "connection.php";
if (!isset($_SESSION["Teacher"])) {
	header("Location: index.php");
	exit;
}

$username = $_SESSION["Teacher"];
$user_name = $username;
// Remove everything after the "@" symbol
$username = substr($username, 0, strpos($username, "@"));

// Split the username into an array based on the "." delimiter
$name_array = explode(".", $username);

// Capitalize the first letter of each name part and join with a space
$final = ucwords(implode(" ", $name_array));

$query_name = "SELECT * FROM teacher_data WHERE email = '$user_name' LIMIT 1";

// execute the query and fetch the result
$result = mysqli_query($conn, $query_name);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// extract the name value from the row and store it in $name variable
$name = $row['name'];




// Define the username variable
$username = $user_name;

// Define an empty array to store unique combinations of email, branch, and subject
$unique_entries = array();

// Prepare a SELECT query to retrieve the email, year_branch_class, and subject columns from the teacher_data table
$query = "SELECT email, year_branch_class, subject FROM teacher_data WHERE email = ?";

// Prepare the query statement with a bound parameter for the username variable
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);

// Execute the query
$stmt->execute();

// Bind the query results to variables
$stmt->bind_result($email, $year_branch_class, $subject);

// Loop over each row in the query results
while ($stmt->fetch()) {
	// Extract the branch from the year_branch_class string (assuming branch is always the second substring separated by "-")
	$branch_parts = explode("-", $year_branch_class);
	$branch = $branch_parts[0] . '-' . $branch_parts[1];

	// Check if the combination of email, branch, and subject already exists in the unique_entries array
	if (!in_array(array($email, $branch, $subject), $unique_entries)) {
		// If the combination doesn't exist, add it to the array
		$unique_entries[] = array($email, $branch, $subject);
	}
}

// Close the query statement
$stmt->close();

// Define an array to store the averages for each unique combination of email, branch, and subject
$avg_avg = array();








?>

<!DOCTYPE html>
<html>

<head>
	<link rel="icon" href="./public/favicon.ico" type="image/x-icon">

	<title>Teacher Feedback</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style1.css">

	<meta name="viewport" content="width=device-width, initial-scale=0">

</head>
<style>
	html,
	body {
		height: 100%;
	}

	body {
		position: relative;
		margin: 0;
		background: linear-gradient(180deg, rgba(253, 92, 92, 0.89) 0%, rgba(255, 168, 0, 0.38) 100%);

		background-repeat: no-repeat;
		background-size: cover;
		background-attachment: fixed;
		padding-bottom: 200px;

	}

	.wrapper {
		min-height: 80%;
		top: 10%;
		/* change the value as per your requirements */

		position: relative;
	}

	.BOX {
		position: relative;
		background-color: aqua;
		margin-left: auto;
		margin-right: auto;

		width: 60%;
		background: linear-gradient(180deg, #F7B5B5 0%, rgba(227, 157, 141, 0.177083) 60.42%, rgba(161, 148, 172, 0) 100%);
		height: auto;
		box-shadow: 0 0 2px;
		border-radius: 58px;
		padding-left: 2%;
		padding-right: 2%;
		padding-top: 2%;
		padding-bottom: 2%;
		z-index: 1;
	}

	.welcome {
		position: relative;
		font-family: "Arial";
		font-style: normal;
		font-weight: 700;
		font-size: 50px;
		line-height: 74px;
		margin: 0;
		top: 10%;
		text-align: center;


		color: rgba(0, 0, 0, 0.74);

	}

	p {
		font-family: "Arial";
		font-style: normal;

		font-size: 18px;
		line-height: 28px;
		margin: 0;
		color: rgba(0, 0, 0, 0.74);
	}

	.leftDesign {
		position: absolute;
		top: 5%;
		left: -150px;
		background: linear-gradient(180deg, rgba(255, 0, 122, 0.46) 0%, rgba(233, 17, 17, 0) 100%);
		;
		height: 400px;
		width: 400px;
		border-radius: 50%;
		z-index: -1;


	}

	.rightDesign {
		position: absolute;
		top: -100px;

		right: -10%;
		background: linear-gradient(180deg, rgba(255, 0, 122, 0.46) 0%, rgba(233, 17, 17, 0) 100%);
		;
		height: 400px;
		width: 400px;
		border-radius: 50%;
		z-index: -1;
	}

	table {
		position: relative;
		border-collapse: collapse;
		width: 80%;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 20px;
		border: 1px solid #ddd;
	}

	th,
	td {
		padding: 8px;
		text-align: left;
		border: 1px solid #ddd;
	}

	th {
		background-color: #f2f2f2;
	}

	.footer_new {
		position: absolute;
		display: flex;
		flex-wrap: wrap;
		bottom: 0;
		height: 60px;
		width: 100%;
		color: black;
		background-color: white;
		box-sizing: border-box;
		/* added */
	}

	.F1,
	.F2,
	.F3 {
		flex-basis: calc((100% / 3) - 2%);
		margin-bottom: 0;
	}

	.F1 {
		text-align: left;
	}

	.F2 {
		text-align: center;
	}

	.F3 {
		text-align: right;
	}

	@media screen and (max-width:600px) {
		body {
			padding-bottom: 100px;
		}

		.footer_new {
			height: 200px;
		}
	}
</style>

<body>

	<div class="leftDesign"></div>
	<div class="rightDesign"></div>

	<h2 class="welcome">
		Welcome <?php echo $final; ?>

	</h2>
	<div class="wrapper">
		<div class="BOX">

			<div align="right">
				<form action="logout.php" method="POST" style="display: inline-block;">
					<a href="logout.php"><button type="submit" name="logout" class="button_css" style="display: inline-block;">Logout</button></a>
				</form>

				<a href="reset.php"><button type="button" class="button_css" style="display: inline-block;">Reset Password</button></a>
			</div>
			<br>
			<p><?php
				// Start the table and output the header row
				echo "<table>";
				echo "<tr><th>Email</th><th>Branch</th><th>Subject</th><th>Average Feedback Score</th></tr>";

				// Loop over each unique combination of email, branch, and subject
				foreach ($unique_entries as $entry) {
					// Extract the email, branch, and subject values from the current entry
					$email = $entry[0];
					$branch = $entry[1];
					$subject = $entry[2];

					$query_match = "SELECT * FROM feedback_report where teacher ='$name' and year_branch_class LIKE '$branch-%' and subject ='$subject'";
					$result_match = mysqli_query($conn, $query_match);

					// Calculate the average of the 'avg' column for the matched rows
					$sum = 0;
					$count = 0;
					while ($row = mysqli_fetch_assoc($result_match)) {
						$sum += $row['avg'];
						$count++;
					}
					$average = ($count > 0) ? ($sum / $count) : 0;

					// Output the row for this combination of email, branch, and subject
					echo "<tr><td>$email</td><td>$branch</td><td>$subject</td><td>$average</td></tr>";
				}

				// End the table
				echo "</table>";
				?>
			</p>
		</div>

	</div>
	<footer class="footer_new">
		<p class='F1'>Feedback | © COPYRIGHT 2023</p>
		<p class='F2'>Ideation By: Head CSE
		<p>
		<p class='F3'>Developed By: <a href='https://www.linkedin.com/in/swayam-pendgaonkar-ab4087232/' target='_blank' class='link' style='color:black'>Swayam Pendgaonkar</a><br />UI/UX: <a href='https://www.linkedin.com/in/sakshamgupta912/' target='_blank' class='link' style='color:black'>Saksham Gupta</a> ,<a href='https://www.linkedin.com/in/yajushreshtha-shukla/' target='_blank' class='link' style='color:black'>Yajushreshtha Shukla</a> </p>

	</footer>
	<script type="text/javascript" src="js.jquery.min.js"></script>
	<script type="text/javascript" src="js.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
</body>

</html>