<?php
session_start();
require_once "connection.php";
if (!isset($_SESSION["Teacher"])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION["Teacher"];
$user_name = $username;
?>

<?php
if (isset($_GET["guest_id"])) {
    $guestId = $_GET["guest_id"];
    
    // Fetch the current is_valid value
    $query_is_valid = "SELECT is_valid FROM guest WHERE id = '$guestId'";
    $result_is_valid = mysqli_query($conn, $query_is_valid);
    $row_is_valid = mysqli_fetch_assoc($result_is_valid);
    $currentIsValid = $row_is_valid['is_valid'];
    
    // Toggle the is_valid value
    $newIsValid = ($currentIsValid == 1) ? 0 : 1;
    
    // Update the is_valid value in the guest table
    $query_update = "UPDATE guest SET is_valid = '$newIsValid' WHERE id = '$guestId'";
    $result_update = mysqli_query($conn, $query_update);
    
    if ($result_update) {
        $_SESSION['update_success'] = true; // Set a session variable to indicate success
    } else {
        $_SESSION['update_success'] = false; // Set a session variable to indicate failure
    }
} else {
    echo "Invalid request";
}

header("Location: teacher.php");
    exit();
?>
