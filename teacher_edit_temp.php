<?php
session_start();

if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}

require "connection.php";
$branch = $_SESSION['branch'];
$branch_teacher = $branch . "_teacher";

// Check for success or error message in session variables
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']); // Remove the session variable
} elseif (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']); // Remove the session variable
}

// Check if search email is provided
$searchEmail = isset($_GET['search_email']) ? $_GET['search_email'] : '';

// Check if delete email(s) is provided
if (isset($_POST['delete_email'])) {
    $deleteEmail = $_POST['delete_email'];

    // Split the email(s) separated by commas
    $emailList = explode(",", $deleteEmail);
    $emailList = array_map('trim', $emailList);

    // Generate placeholders for the email(s)
    $placeholders = rtrim(str_repeat('?,', count($emailList)), ',');

    // Prepare the delete statement
    $deleteStmt = $conn->prepare("DELETE FROM $branch_teacher WHERE email IN ($placeholders)");

    // Bind the email(s) to the placeholders
    $deleteStmt->bind_param(str_repeat('s', count($emailList)), ...$emailList);

    // Execute the delete statement
    if ($deleteStmt->execute()) {
        $_SESSION['success_message'] = "Teacher(s) deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting teacher(s): " . $deleteStmt->error;
    }

    // Close the delete statement
    $deleteStmt->close();

    // Redirect back to the current page
    header("Location: $_SERVER[PHP_SELF]");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Teacher Data Edit</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background-color: lightgray;
        }
    </style>
    <script>
        function saveChanges() {
            var table = document.getElementById("data-table");
            var rowCount = table.rows.length;

            // Loop through each row
            for (var i = 1; i < rowCount; i++) {
                var row = table.rows[i];
                var id = row.cells[0].innerHTML;
                var email = row.cells[1].innerHTML;
                var name = row.cells[2].innerHTML;
                var subject = row.cells[3].innerHTML;
                var acad_year = row.cells[4].innerHTML;
                var branch = row.cells[5].innerHTML;
                var class_ = row.cells[6].innerHTML;

                // Send AJAX request to save changes in the database
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "teacher_edit_process.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === "success") {
                            alert("Teacher Data Updated Successfully!");
                        } else {
                            alert("Error updating data: " + xhr.responseText);
                        }
                    }
                };
                xhr.send("id=" + id + "&email=" + email + "&name=" + name + "&subject=" + subject + "&acad_year=" + acad_year + "&branch=" + branch + "&class=" + class_);
            }
        }
    </script>
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
        <label for="search_email">Search by Email:</label>
        <input type="text" name="search_email" id="search_email" value="<?php echo $searchEmail; ?>">
        <input type="submit" value="Search">
    </form>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="delete_email">Delete by Email:</label>
        <input type="text" name="delete_email" id="delete_email" placeholder="Enter email(s) separated by commas">
        <input type="submit" value="Delete">
    </form>

    <table id="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Academic Year</th>
                <th>Branch</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Pagination
            $entriesPerPage = 20;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start = ($page - 1) * $entriesPerPage;

            // Query to fetch entries from the database
            $searchQuery = "";
            if (!empty($searchEmail)) {
                $searchQuery = " WHERE email LIKE '%$searchEmail%'";
            }
            $query = "SELECT * FROM $branch_teacher$searchQuery LIMIT $start, $entriesPerPage";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $email = $row['email'];
                    $name = $row['name'];
                    $subject = $row['subject'];
                    $acad_year = $row['acad_year'];
                    $branch = $row['branch'];
                    $class = $row['class'];
                    echo "<tr ondblclick=\"this.contentEditable='true';\" onblur=\"this.contentEditable='false';\">
                              <td>$id</td>
                              <td>$email</td>
                              <td>$name</td>
                              <td>$subject</td>
                              <td>$acad_year</td>
                              <td>$branch</td>
                              <td>$class</td>
                            </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No entries found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Total number of entries
    $totalEntriesQuery = "SELECT COUNT(*) AS total FROM $branch_teacher$searchQuery";
    $totalResult = $conn->query($totalEntriesQuery);
    $totalEntries = $totalResult->fetch_assoc()['total'];

    // Pagination links
    $totalPages = ceil($totalEntries / $entriesPerPage);
    echo "<p>Total Entries: $totalEntries</p>";
    echo "<p>Page: $page / $totalPages</p>";
    if ($page > 1) {
        echo "<a href='?page=" . ($page - 1) . "'>Previous</a>";
    }
    if ($page < $totalPages) {
        echo "<a href='?page=" . ($page + 1) . "'>Next</a>";
    }

    // Close database connection
    $conn->close();
    ?>

    <button onclick="saveChanges()">Save Changes</button>
</body>

</html>
