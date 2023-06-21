<!DOCTYPE html>
<html>
<head>
  <title>Editable Table Example</title>
  <style>
    table {
      border-collapse: collapse;
    }

    th, td {
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

        // Send AJAX request to save changes in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "student_edit_process.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("id=" + id + "&prn=" + prn + "&name=" + name);
      }
    }
  </script>
</head>
<body>
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

    // Pagination
    $entriesPerPage = 20;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $entriesPerPage;

    // Query to fetch entries from the database
    $query = "SELECT * FROM login LIMIT $start, $entriesPerPage";
    $result = $conn->query($query);
    ?>

    <table id="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>PRN</th>
          <th>Name</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $prn = $row['email'];
            $name = $row['username'];
            echo "<tr ondblclick=\"this.contentEditable='true';\" onblur=\"this.contentEditable='false';\">
                  <td>$id</td>
                  <td>$prn</td>
                  <td>$name</td>
                </tr>";
          }
        } else {
          echo "<tr><td colspan='3'>No entries found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <?php
    // Total number of entries
    $totalEntriesQuery = "SELECT COUNT(*) AS total FROM login";
    $totalResult = $conn->query($totalEntriesQuery);
    $totalEntries = $totalResult->fetch_assoc()['total'];

    // Pagination links
    $totalPages = ceil($totalEntries / $entriesPerPage);
    echo "<p>Total Entries: $totalEntries</p>";
    echo "<p>Page: $page / $totalPages</p>";
    if ($page > 1) {
      echo "<a href='?page=".($page - 1)."'>Previous</a>";
    }
    if ($page < $totalPages) {
      echo "<a href='?page=".($page + 1)."'>Next</a>";
    }

    // Close database connection
    $conn->close();
  ?>

  <button onclick="saveChanges()">Save Changes</button>
</body>
</html>