<?php

session_start();
if (!isset($_SESSION['coordinator'])) {
    header('Location: index.php');
    exit;
}

require "connection.php";
$branch = $_SESSION['branch'];
$branch_student = $branch . '' . "_student";

// Check for success or error message in session variables
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']); // Remove the session variable
} elseif (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']); // Remove the session variable
}

// Check if search PRN is provided
$searchPRN = isset($_GET['search_prn']) ? $_GET['search_prn'] : '';

// Check if delete PRN(s) is provided
if (isset($_POST['delete_prn'])) {
    $deletePRN = $_POST['delete_prn'];

    // Split the PRN(s) separated by commas
    $prnList = explode(",", $deletePRN);
    $prnList = array_map('trim', $prnList);

    // Generate placeholders for the PRN(s)
    $placeholders = rtrim(str_repeat('?,', count($prnList)), ',');

    // Prepare the delete statement
    $deleteStmt = $conn->prepare("DELETE FROM $branch_student WHERE prn IN ($placeholders)");

    // Bind the PRN(s) to the placeholders
    $deleteStmt->bind_param(str_repeat('s', count($prnList)), ...$prnList);

    // Execute the delete statement
    if ($deleteStmt->execute()) {
        $_SESSION['success_message'] = "Student(s) deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting student(s): " . $deleteStmt->error;
    }

    // Close the delete statement
    $deleteStmt->close();

    $reorderStmt = $conn->prepare("ALTER TABLE $branch_student ORDER BY id ASC");
    $reorderStmt->execute();
    $reorderStmt->close();

    // Redirect back to the current page
    header("Location: $_SERVER[PHP_SELF]");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Data Edit</title>
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
                var prn = row.cells[1].innerHTML;
                var name = row.cells[2].innerHTML;
                var email = row.cells[3].innerHTML;
                var open = row.cells[4].innerHTML;
                var general = row.cells[5].innerHTML;
                var acad_year = row.cells[6].innerHTML;
                var branch = row.cells[7].innerHTML;
                var class_ = row.cells[8].innerHTML;
                var semester = row.cells[9].innerHTML;
                var crnt_year = row.cells[10].innerHTML;

                // Send AJAX request to save changes in the database
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "student_edit_process.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === "success") {
                            alert("Student Data Updated Successfully!");
                        } else {
                            alert("Error updating data: " + xhr.responseText);
                        }
                    }
                };
                xhr.send("id=" + id + "&prn=" + prn + "&name=" + name + "&email=" + email + "&open=" + open + "&general=" + general + "&acad_year=" + acad_year + "&branch=" + branch + "&class=" + class_ + "&semester=" + semester + "&crnt_year=" + crnt_year);
            }
        }
    </script>
</head>

<body>

    <!-- Search Form -->
    <form method="GET" action="">
        <input type="text" name="search_prn" placeholder="Search by PRN" value="<?php echo $searchPRN; ?>">
        <input type="submit" value="Search">
    </form>

    <!-- Delete Form -->
    <form method="POST" action="">
        <input type="text" name="delete_prn" placeholder="Enter PRN(s) to delete">
        <input type="submit" value="Delete">
    </form>

    <?php
    // Pagination
    $entriesPerPage = 20;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $entriesPerPage;

    // Query to fetch entries from the database
    $query = "SELECT * FROM $branch_student WHERE prn LIKE '%$searchPRN%' LIMIT $start, $entriesPerPage";
    $result = $conn->query($query);
    ?>

    <table id="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>PRN</th>
                <th>Name</th>
                <th>Email</th>
                <th>Open</th>
                <th>General</th>
                <th>Acad Year</th>
                <th>Branch</th>
                <th>Class</th>
                <th>Semester</th>
                <th>Current Year</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $prn = $row['prn'];
                    $name = $row['name'];
                    $email = $row['email'];
                    $open = $row['open'];
                    $general = $row['general'];
                    $acad_year = $row['acad_year'];
                    $branch = $row['branch'];
                    $class = $row['class'];
                    $semester = $row['semester'];
                    $crnt_year = $row['crnt_year'];

                    echo "<tr ondblclick=\"this.contentEditable='true';\" onblur=\"this.contentEditable='false';\">
                          <td>$id</td>
                          <td>$prn</td>
                          <td>$name</td>
                          <td>$email</td>
                          <td>$open</td>
                          <td>$general</td>
                          <td>$acad_year</td>
                          <td>$branch</td>
                          <td>$class</td>
                          <td>$semester</td>
                          <td>$crnt_year</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No entries found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Total number of entries
    $totalEntriesQuery = "SELECT COUNT(*) AS total FROM $branch_student WHERE prn LIKE '%$searchPRN%'";
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
