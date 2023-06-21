<?php


require('connection.php');

if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    $email = $_GET['email'];
    $resetToken = $_GET['reset_token'];

    // Check if the reset token is valid
    $sql = "SELECT * FROM login WHERE email='$email' AND resettoken='$resetToken'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'];
            $newPassword = base64_encode($newPassword);

            // Retrieve the old password from the table
            $sql = "SELECT password FROM login WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $oldPassword = $row['password'];

                // Check if the new password matches the old password
                if ($newPassword == $oldPassword) {
                    echo "<script>
                        alert('New password cannot be the same as the old password.');
                        window.location.href = 'forgot-password-process.php?email=$email&reset_token=$resetToken';
                    </script>";
                } else {
                    // Validate and update the password in the database

                    $sql = "UPDATE login SET password='$newPassword' WHERE email='$email'";
                    if ($conn->query($sql) === TRUE) {
                        // Clear the reset token after password reset
                        $sql = "UPDATE login SET resettoken = NULL WHERE email = '$email'";
                        $conn->query($sql);

                        echo "<script>
                            alert('Password reset successful.');
                            window.location.href = 'index.php';
                        </script>";
                    } else {
                        echo "<script>
                            alert('Error updating password.');
                            window.location.href = 'reset-password.php?email=$email&reset_token=$resetToken';
                        </script>";
                    }
                }
            } else {
                echo "<script>
                    alert('Error matching with old passwords.');
                    window.location.href = 'reset-password.php?email=$email&reset_token=$resetToken';
                </script>";
            }
        }
    } else {
        echo "<script>
            alert('Invalid reset link.');
            window.location.href = 'index.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid reset link.');
        window.location.href = 'index.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <input type="submit" name="submit" value="Reset Password">
    </form>
</body>
</html>
