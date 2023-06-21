<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "sit_feedback";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];
  $prn = sanitizeInput($_POST["prn"]);
  $name = sanitizeInput($_POST["name"]);

  // Update the data in the database
  $updateQuery = "UPDATE login SET email = '$prn', username = '$name' WHERE id = $id";
  if ($conn->query($updateQuery) === TRUE) {
    echo "Data updated successfully.";
  } else {
    echo "Error updating data: " . $conn->error;
  }
}

// Function to sanitize input
function sanitizeInput($input) {
    $host = "localhost";
$username = "root";
$password = "";
$database = "sit_feedback";
$conn = new mysqli($host, $username, $password, $database);
  $sanitized = strip_tags($input); // Remove HTML tags
  $sanitized = htmlspecialchars($sanitized); // Convert special characters to HTML entities
  $sanitized = $conn->real_escape_string($sanitized); // Prevent SQL injection
  return $sanitized;
}

// Close database connection
$conn->close();
?>