<?php
session_start();
if (!isset($_SESSION['SuperAdmin'])) {
	header('Location: index.php');
	exit;
}


require_once "connection.php";

$username = $_SESSION['SuperAdmin'];
$username_parts = explode("@", $username);
$username_prefix = $username_parts[0];
$text = strtolower($username_prefix); // convert all characters to lowercase
$text_final = ucfirst($text);


if (!$conn) {
	die('Error connecting to the database: ' . mysqli_connect_error());
}

// add a new coordinator
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coordinator'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$branch = $_POST['branch'];

	// validate email address
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// hash the password
		$encoded_string = base64_encode($password);


		// insert the new coordinator into the database
		$sql = "INSERT INTO login (username, password, branch, role, crnt_year, is_valid) VALUES ('$email', '$encoded_string', '$branch','coordinator','2023','1')";
		if (mysqli_query($conn, $sql)) {
			echo "<script>alert('Coordinator added successfully!');</script>";
		} else {
			$error_message = mysqli_error($conn);
			echo "<script>alert('Error adding coordinator: $error_message');</script>";
		}
	} else {
		echo "<script>alert('Invalid email address!');</script>";
	}
}

// delete an existing coordinator
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_coordinator'])) {
	$email = $_POST['email'];

	// delete the coordinator from the database
	$sql = "DELETE FROM login WHERE username='$email'";
	if (mysqli_query($conn, $sql)) {
		echo "<script>alert('Coordinator deleted successfully!');</script>";
	} else {
		$error_message1 = mysqli_error($conn);
		echo "<script>alert('Error deleting coordinator: $error_message1');</script>";
	}
}

// get all the coordinators from the database
$sql = "SELECT * FROM login WHERE role = 'coordinator'";
$result = mysqli_query($conn, $sql);




if (isset($_POST['download_passwords'])) {
	// Set the filename for the downloaded CSV file
	$filename = 'teacher_passwords.csv';

	// Set the headers for the CSV file
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '"');

	// Open a new file handle to write the CSV data
	$file = fopen('php://output', 'w');

	// Add the CSV header row
	fputcsv($file, array('Username', 'Password'));

	// Query the login table to get all teachers' usernames and decoded passwords
	$query2 = "SELECT * FROM login WHERE role = 'teacher'";
	$result = mysqli_query($conn, $query2);

	// Loop through each row in the result set and add it to the CSV file
	while ($row = mysqli_fetch_assoc($result)) {
		$password  = base64_decode($row['password']);
		fputcsv($file, array($row['username'], $password));
	}

	// Close the file handle and exit the script
	fclose($file);
	exit();
}

// (Teacher) First, check if the button is clicked
if (isset($_POST['teacher_on_off'])) {
	// Then, get the value of the clicked button
	$button_value = $_POST['teacher_on_off'];

	// Establish a database connection

	// Check if the connection was successful
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// If the "on" button is clicked, update the is_valid column to 1 for all rows where the role is "teacher"
	if ($button_value == "on") {
		$sql = "UPDATE login SET is_valid=1 WHERE role='teacher' ";
	}

	// If the "off" button is clicked, update the is_valid column to 0 for all rows where the role is "teacher"
	if ($button_value == "off") {
		$sql = "UPDATE login SET is_valid=0 WHERE role='teacher' ";
	}

	// Execute the SQL query
	if (mysqli_query($conn, $sql)) {
		if ($button_value == "on") {
			echo "<script>alert('Teacher Login Turned ON');</script>";
		} else if ($button_value == "off") {
			echo "<script>alert('Teacher Login Turned OFF');</script>";
		}
	} else {
		echo "Error changing modes: " . mysqli_error($conn);
	}
}

// close the database connection
mysqli_close($conn);



?>



<!DOCTYPE html>
<html>

<head>

	<title>Admin Panel</title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style1.css">
	<meta http-equiv="Cache-control" content="no-cache">

	<style>
		form {
			display: inline-block;
		}

		form label,
		form input {
			display: inline-block;
			vertical-align: middle;
			margin: 5px;
		}


		html,
		body {
			padding: 0;
			height: 100%;
		}

		body {
			position: relative;
			margin: 0;

			background: linear-gradient(107.52deg, rgba(71, 153, 247, 0.95) 0%, rgba(175, 137, 255, 0.95) 47.18%, rgba(255, 230, 239, 0.95) 98.04%);


			background-repeat: no-repeat;
			background-size: cover;
			background-attachment: fixed;

		}


		.BOX {
			margin-left: auto;
			margin-right: auto;
			padding: 2%;
			top: 10%;
			position: relative;
			width: 60%;

			background: linear-gradient(180deg, rgba(255, 255, 255, 0.71) 0%, rgba(255, 255, 255, 0.83) 100%);
			box-shadow: 0px 4px 27px rgba(0, 0, 0, 0.25);
			border-radius: 58px;
		}

		.InputField {
			position: relative;
			width: 100%;
			height: 35px;

			border: 0px;
			background: white;
			box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
			border-radius: 39px;



			padding-left: 20px;
			font-family: 'Arial';
			font-style: normal;
			font-weight: 400;
			font-size: 20px;
			line-height: 46px;

			color: rgba(0, 0, 0, 0.782);
		}

		.AddCoordinatorButton {
			background: linear-gradient(107.52deg, rgba(71, 153, 247, 0.95) 0%, rgba(175, 137, 255, 0.95) 100%);
			;
			border: 0px;

			padding-left: 10px;
			padding-right: 10px;
			border-radius: 37px;

			font-family: 'Arial';
			font-style: normal;

			font-size: 15px;
			line-height: 23px;
		}

		.DesginLeft {
			margin: 0;
			position: absolute;
			height: 100%;
		}

		.DesginRight {
			margin: 0;
			right: 0;
			position: absolute;
			height: 100%;
		}
	</style>
	<link rel="icon" href="./public/favicon.ico" type="image/x-icon">

	<script>
		function editColumn(rowId, column, value) {
			var newValue = prompt("Enter the new value:", value);
			if (newValue !== null && newValue !== "") {
				// Update the cell value on the frontend
				document.getElementById('row-' + rowId).querySelector('td:nth-child(' + (getColumnIndex(column) + 1) + ')').innerText = newValue;

				// Send an AJAX request to update the value in the backend
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						// Handle the response if needed
						console.log(this.responseText);
					}
				};
				xhttp.open("POST", "update.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("rowId=" + rowId + "&column=" + column + "&value=" + encodeURIComponent(newValue));
			}
		}

		function getColumnIndex(column) {
			// Map the column name to the column index
			switch (column) {
				case "email":
					return 1;
				case "branch":
					return 2;
					// Add more columns if needed
			}
			return -1;
		}
	</script>

</head>

<body>
	<!-- <img src="./public/IconDesignAdmin.1.svg" class="DesginLeft"></img>
	<img src="./public/IconDesignAdmin.2.svg" class="DesginRight"></img> -->
	<div class="BOX">

		<div align="right">
			<form action="logout.php" method="POST">

				<a href="logout.php"><button type="button" class="button_css">Logout</button></a>
			</form>
			<a href="reset.php"><button type="button" class="button_css">Reset Password</button></a>
		</div>
		<h2 class="text-center" style="position:relative;top:10px;font-style: normal;font-weight: 700;">
			Welcome SuperAdmin
			<!-- <?php echo $text_final; ?> -->
		</h2>
		<br>

		<!-- form to add a new coordinator -->
		<form method="POST" align="center" style="top:120px;left: 50%;transform: translate(-50%, -50%);position:relative;left:50%">
			<table>
				<tr>

					<input class="InputField" type="email" placeholder="Email" id="email" name="email" required placeholder="Email">
				</tr>
				<br>
				<tr>

					<input class="InputField" type="password" placeholder="Password" id="password" name="password" required>
				</tr>
				<br>
				<tr>

					<input class="InputField" type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required>
				</tr>
				<br>
				<tr>

					<input class="InputField" type="text" placeholder="Branch" id="branch" name="branch" required>
				</tr>
				<br>
				<br>
				<tr>

					<button class="AddCoordinatorButton" type="submit" name="add_coordinator">Add Coordinator</button>
				</tr>
			</table>
		</form>

		<br><br>
		<?php
		// display the coordinators in a table
		echo "<table style='border-collapse: collapse;' align='center'>";
		echo "<tr><th style='padding: 10px; border: 1px solid black;'>Sr.No. </th><th style='padding: 10px; border: 1px solid black;'>Email</th><th style='padding: 10px; border: 1px solid black;'>Branch</th><th style='padding: 10px; border: 1px solid black;'>Action</th></tr>";
		$index = 1;
		while ($row = mysqli_fetch_assoc($result)) {
			$email = $row['username'];
			$branch = $row['branch'];

			echo "<tr id='row-$index'><td style='padding: 10px; border: 1px solid black;'>$index</td><td style='padding: 10px; border: 1px solid black;' ondblclick='editColumn($index, \"email\", \"$email\")'>$email</td><td style='padding: 10px; border: 1px solid black;' ondblclick='editColumn($index, \"branch\", \"$branch\")'>$branch</td><td style='padding: 10px; border: 1px solid black;'><form method='POST'><input type='hidden' name='email' value='$email'><button type='submit' name='delete_coordinator'>Delete</button></form></td></tr>";
			$index++;
		}
		echo "</table>";
		echo "<br>";
		?>
		<br />
		<form method="POST" align="center" style="left: 50%;transform: translate(-50%, -50%);position:relative;left:50%">
			Download Teacher Password:
			<button type="submit" name="download_passwords" style="background:linear-gradient(107.52deg, rgba(71, 153, 247, 0.95) 0%, rgba(175, 137, 255, 0.95) 100%);
            ;
            border: 0px;

            padding-left: 10px;
            padding-right: 10px;
            border-radius: 37px;

            font-family: 'Arial';
            font-style: normal;
        
            font-size: 15px;
            line-height: 23px;"> Download Password </button>
		</form>
		<br />
		<br />
		<form method="POST" align="center" style="left: 50%;transform: translate(-50%, -50%);position:relative;left:50%">
			Turn the teacher login ON or OFF:
			<button class="DownloadButtom" type="submit" name="teacher_on_off" value="on" style="background:green;">ON</button>
			<button class="DownloadButtom" type="submit" name="teacher_on_off" value="off" style="background:red;">OFF</button>
		</form>




	</div>

	<script type="text/javascript" src="js.jquery.min.js"></script>
	<script type="text/javascript" src="js.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
</body>

</html>