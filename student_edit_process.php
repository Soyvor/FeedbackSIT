<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}

require "connection.php";
$branch = $_SESSION['branch'];
$branch_student = $branch.''."_student";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];
  $prn = sanitizeInput($_POST["prn"]);
  $name = sanitizeInput($_POST["name"]);
  $email = sanitizeInput($_POST["email"]);
  $open = sanitizeInput($_POST["open"]);
  $general = sanitizeInput($_POST["general"]);
  $acad_year = sanitizeInput($_POST["acad_year"]);
  $branch = sanitizeInput($_POST["branch"]);
  $class = sanitizeInput($_POST["class"]);
  $semester = sanitizeInput($_POST["semester"]);
  $crnt_year = sanitizeInput($_POST["crnt_year"]);

  // Update the data in the database
  $updateQuery = "UPDATE $branch_student SET prn = '$prn', name = '$name', email = '$email', open = '$open', general = '$general', acad_year = '$acad_year', branch = '$branch', class = '$class', semester = '$semester', crnt_year = '$crnt_year' WHERE id = $id";
  if ($conn->query($updateQuery) === TRUE) {
    echo "success";
} else {
    echo $conn->error;
}
}

// Function to sanitize input
function sanitizeInput($input) {

  require  "connection.php";
  $sanitized = strip_tags($input); // Remove HTML tags
  $sanitized = htmlspecialchars($sanitized); // Convert special characters to HTML entities
  $sanitized = $conn->real_escape_string($sanitized); // Prevent SQL injection
  return $sanitized;
}

// Close database connection
$conn->close();
?>
