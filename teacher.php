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

$query_name = "SELECT * FROM login WHERE username = '$user_name' LIMIT 1";

// execute the query and fetch the result
$result = mysqli_query($conn, $query_name);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// extract the name value from the row and store it in $name variable
// $name = $row['name'];




// Define the username variable
$username = $user_name;

// Define an empty array to store unique combinations of email, branch, and subject
$unique_entries = array();

$branch_tables = array("cs_teacher", "mech_teacher"); // Add more branch-specific tables as needed

foreach ($branch_tables as $branch_table) {
	$query = "SELECT email, subject, branch, acad_year FROM $branch_table WHERE email = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->bind_result($email, $subject, $branch, $acad_year);
	while ($stmt->fetch()) {
		if (!in_array(array($email, $branch, $subject, $acad_year), $unique_entries)) {
			$unique_entries[] = array($email, $branch, $subject, $acad_year);
		}
	}
	$stmt->close();
}

$avg_avg = array();

$query_name = "SELECT * FROM cs_teacher WHERE email = '$email' LIMIT 1";

// execute the query and fetch the result
$result = mysqli_query($conn, $query_name);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$name = $row['name'];




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


	<style>
    /* Styling for Navigation Sidebar */
    .sidebar {
        width: 20%;
        background-color: #f4f4f4;
        height: 100vh; /* Adjust the height as needed */
        float: left;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 10px;
        text-align: center;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #333;
        display: block;
    }

    /* Styling for Main Content */
    .content {
        width: 80%;
        float: right;
    }
</style>



	<script>
    document.addEventListener("DOMContentLoaded", function() {
        const viewFeedbackButton = document.getElementById("view-feedback-btn");
        const assignGuestButton = document.getElementById("assign-guest-btn");
        const viewFeedbackPage = document.querySelector(".view-feedback-page");
        const assignGuestPage = document.querySelector(".assign-guest-page");

        viewFeedbackButton.addEventListener("click", function() {
            viewFeedbackPage.style.display = "block";
            assignGuestPage.style.display = "none";
        });

        assignGuestButton.addEventListener("click", function() {
            assignGuestPage.style.display = "block";
            viewFeedbackPage.style.display = "none";
        });
    });
</script>
</head>
<!-- <style>
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
</style> -->

<body>

	<div class="leftDesign"></div>
	<div class="rightDesign"></div>

	<h2 class="welcome">
		Welcome <?php echo $final; ?>

	</h2>
	<div class="sidebar">
        <ul>
            <li><a href="#" id="view-feedback-btn">View Feedback</a></li>
            <li><a href="#" id="assign-guest-btn">Assign Guest</a></li>
            <!-- Add other navigation links here -->
        </ul>
    </div>
	<div class="wrapper">
		<div class="BOX">

		<div align="right">
            <form action="logout.php" method="POST" style="display: inline-block;">
                <a href="logout.php"><button type="submit" name="logout" class="button_css" style="display: inline-block;">Logout</button></a>
            </form>
            <a href="reset.php"><button type="button" class="button_css" style="display: inline-block;">Reset Password</button></a>
        </div>
			<br>
			<div class="view-feedback-page">
			<p><?php
				// Start the table and output the header row
				echo "<table>";
				echo "<tr><th>Email</th><th>Year - Branch</th><th>Subject</th><th>Average Feedback Score</th></tr>";

				// Loop over each unique combination of email, branch, and subject
				foreach ($unique_entries as $entry) {
					$email = $entry[0];
					$branch = $entry[1];
					$subject = $entry[2];
					$acad_year = $entry[3];
					$query_match = "SELECT * FROM cs_feedback WHERE teacher = '$name' AND branch = '$branch' AND acad_year = '$acad_year' AND subject = '$subject'";
					$result_match = mysqli_query($conn, $query_match);
					$sum = 0;
					$count = 0;
					while ($row = mysqli_fetch_assoc($result_match)) {
						$sum += $row['avg'];
						$count++;
					}
					$average = ($count > 0) ? ($sum / $count) : 0;
					$avg_avg[] = $average;
				
					// Output the row for this combination of email, branch, and subject
					echo "<tr><td>$email</td><td>$acad_year-$branch</td><td>$subject</td><td>$average</td></tr>";
				}

				// End the table
				echo "</table>";
				?>
			</p>
			</div>
		</div>
		<div class="assign-guest-page" style="display: none;">
        <!-- Content for the Assign Guest page -->
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