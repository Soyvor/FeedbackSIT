<?php
// Start the session and check if the user is logged in

session_start();


// Check if user is not logged in and redirect to login page
if (!isset($_SESSION['usertype'])) {
    header('Location: index.php');
    exit;
}


// Connect to the database
include_once "connection.php";

// Get the form data
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Get the username of the logged-in user
if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] == "superadmin") {
        $username = $_SESSION['SuperAdmin'];
    } elseif ($_SESSION['usertype'] == "coordinator") {
        $username = $_SESSION['coordinator'];
    } elseif ($_SESSION['usertype'] == "student") {
        $username = $_SESSION['student'];
    } elseif ($_SESSION['usertype'] == "teacher") {
        $username = $_SESSION['Teacher'];
    }
}

$current_password = base64_encode($current_password);
$new_password = base64_encode($new_password);
$confirm_password = base64_encode($confirm_password);

// Check if the current password matches the one in the database
$sql = "SELECT * FROM tbluser WHERE username='$username' AND password='$current_password'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    // Check if the new password is different from the old password
    if ($new_password != $current_password) {
        // If the new password is different, update the password in the database
        $sql = "UPDATE tbluser SET password='$confirm_password' WHERE username='$username'";
        mysqli_query($conn, $sql);
        // Show a success message using JavaScript
        echo "<script>
                if(window.confirm('Password changed successfully!')){
                    window.location.href = 'index.php';
                }
                else{
                    window.location.href = 'index.php';
                }
             </script>";

        // Reset the password reset session variable
        
        // Logout the user and redirect to the index page
        session_unset();
        session_destroy();
        
        exit();
    } else {
        // If the new password is same as the old password, show an error message using JavaScript
        
        // Redirect back to the reset page
        echo "<script>
                if(window.confirm('New password cannot be same as old password!')){
                    window.location.href = 'reset.php';
                }
                else{
                    window.location.href = 'reset.php';
                }
             </script>";
        
        exit();
    }
} else {
    // If the current password is incorrect, show an error message using JavaScript
    // Redirect back to the reset page
    echo "<script>
                if(window.confirm('Current password is incorrect!')){
                    window.location.href = 'reset.php';
                }
                else{
                    window.location.href = 'reset.php';
                }
             </script>";
    
    exit();
}

?>
