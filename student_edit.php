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
        var id = row.cells[0].textContent;
        var prn = row.cells[1].textContent;
        var name = row.cells[2].textContent;
        var email = row.cells[3].textContent;
        var open = row.cells[4].textContent;
        var general = row.cells[5].textContent;
        var acad_year = row.cells[6].textContent;
        var branch = row.cells[7].textContent;
        var class_ = row.cells[8].textContent;
        var semester = row.cells[9].textContent;
        var crnt_year = row.cells[10].textContent;

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

  <?php


  // Pagination
  $entriesPerPage = 20;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $start = ($page - 1) * $entriesPerPage;

  // Query to fetch entries from the database
  $query = "SELECT * FROM $branch_student LIMIT $start, $entriesPerPage";
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
  $totalEntriesQuery = "SELECT COUNT(*) AS total FROM $branch_student";
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