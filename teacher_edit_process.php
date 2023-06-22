<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}

require "connection.php";
$branch = $_SESSION['branch'];
$branch_teacher = $branch . "_teacher";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];
  $email = sanitizeInput($_POST["email"]);
  $name = sanitizeInput($_POST["name"]);
  $subject = sanitizeInput($_POST["subject"]);
  $acad_year = sanitizeInput($_POST["acad_year"]);
  $branch = sanitizeInput($_POST["branch"]);
  $class = sanitizeInput($_POST["class"]);

  // Update the data in the database
  $updateQuery = "UPDATE $branch_teacher SET email = '$email', name = '$name', subject = '$subject', acad_year = '$acad_year', branch = '$branch', class = '$class' WHERE id = $id";
  if ($conn->query($updateQuery) === TRUE) {
    echo "success";
} else {
    echo $conn->error;
}
}


// Function to sanitize input
function sanitizeInput($input) {
    
    require "connection.php";
    $sanitized = strip_tags($input); // Remove HTML tags
    $sanitized = htmlspecialchars($sanitized); // Convert special characters to HTML entities
    $sanitized = $conn->real_escape_string($sanitized); // Prevent SQL injection
    return $sanitized;
}

// Close database connection
$conn->close();
?>
