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
    $ids = json_decode($_POST["ids"]);

    // Delete rows from the database
    $deleteQuery = "DELETE FROM $branch_teacher WHERE id IN (" . implode(",", $ids) . ")";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "success";
    } else {
        echo $conn->error;
    }
}

// Close database connection
$conn->close();
?>
