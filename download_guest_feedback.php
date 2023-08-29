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
if (isset($_GET["guest_id"]) && isset($_GET["guest_name"])) {
    $guestId = $_GET["guest_id"];
    $guestName = $_GET["guest_name"];
    
    // Get guest details from the guest table
    $query_guest = "SELECT * FROM guest WHERE id = '$guestId'";
    $result_guest = mysqli_query($conn, $query_guest);
    $guest_row = mysqli_fetch_assoc($result_guest);
    $guest_name = $guest_row['guest_name'];
    $guest_topic = $guest_row['guest_topic'];
    $guest_date_from = $guest_row['guest_date_from'];
    $guest_date_to = $guest_row['guest_date_to'];
    $course_club = $guest_row['course_club'];
    
    // Get feedback details for the guest from the guest_feedback table
    $query_feedback = "SELECT student_email, avg, q1, q2, q3, q4, q5, q6, q7, q8, q9 FROM guest_feedback WHERE guest_id = '$guestId'";
    $result_feedback = mysqli_query($conn, $query_feedback);
    
    // Set the CSV file headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $guestName . '.csv"');
    
    // Create a file pointer (output stream) to send the CSV data
    $output = fopen('php://output', 'w');
    
    // Write the CSV header
    fputcsv($output, ['Student Email', 'Guest Name', 'Guest Topic', 'Guest Date From','Guest Date To','Course or Student Club', 'Average', 'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9']);
    
    // Write feedback data rows to the CSV
    while ($feedback_row = mysqli_fetch_assoc($result_feedback)) {
        $csvRow = [
            $feedback_row['student_email'],
            $guest_name,
            $guest_topic,
            $guest_date_from,
            $guest_date_to,
            $course_club,
            $feedback_row['avg'],
            $feedback_row['q1'],
            $feedback_row['q2'],
            $feedback_row['q3'],
            $feedback_row['q4'],
            $feedback_row['q5'],
            $feedback_row['q6'],
            $feedback_row['q7'],
            $feedback_row['q8'],
            $feedback_row['q9']
        ];
        fputcsv($output, $csvRow);
    }
    
    // Close the file pointer
    fclose($output);
    
    // Exit to prevent further output
    exit();
} else {
    // Handle invalid request
    echo "Invalid request";
}

header("Location: teacher.php");
    exit();
?>
