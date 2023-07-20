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

?>

<!DOCTYPE html>
<html>

<head>

    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

    <!-- Include Footer CSS -->
    <link rel="stylesheet" href="./css/footer_style.css">

    <title>Teacher Data Edit</title>
    <style>
        body {
            background-color: #1C2631 !important;

        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: rgba(217, 217, 217, 0.09);
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px black;
        }

        .my-float {
            margin-top: 22px;
        }


        h1 {
            color: #716eb6;
            text-align: center;
        }

        /* Table styles */

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        td,
        th {
            padding: 0;
            text-align: left;
        }

        td:first-of-type {
            padding-left: 36px;
            width: 66px;
        }

        .c-table {
            -moz-border-radius: 10px;
            -webkit-border-radius: 15px;
            background-color: #DFDFDF;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.25;
            margin-bottom: 24px;
            width: 100%;
        }

        .c-table__cell {
            padding: 12px 6px 12px 12px;
            word-wrap: break-word;
        }

        .c-table__header tr {
            border-radius: 15px;
            color: #fff;
        }

        .c-table__header th {
            background-color: #425361;
            padding: 18px 6px 18px 12px;
        }

        .c-table__header th:first-child {
            border-top-left-radius: 10px;
        }

        .c-table__header th:last-child {
            border-top-right-radius: 10px;
        }

        .c-table__body tr {
            border-bottom: 1px solid rgba(113, 110, 182, 0.15);
        }

        .c-table__body tr:last-child {
            border-bottom: none;
        }

        .c-table__body tr:hover {
            background-color: rgba(113, 110, 182, 0.15);
            color: #272b37;
        }

        .c-table__label {
            display: none;
        }

        /* Mobile table styles */

        @media only screen and (max-width: 767px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            td:first-child {
                padding-top: 24px;
            }

            td:last-child {
                padding-bottom: 24px;
            }

            .c-table {
                border: 1px solid rgba(113, 110, 182, 0.15);
                font-size: 15px;
                line-break: 1.2;
            }

            .c-table__header tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .c-table__cell {
                padding: 12px 24px;
                position: relative;
                width: 100%;
                word-wrap: break-word;
            }

            .c-table__label {
                color: #272b37;
                display: block;
                font-size: 10px;
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 6px;
                text-transform: uppercase;
            }

            .c-table__body tr:hover {
                background-color: transparent;
            }

            .c-table__body tr:nth-child(odd) {
                background-color: rgba(113, 110, 182, 0.04);
            }



        }



        .form {
            border-radius: 10px;
        }

        p {
            color: #DFDFDF;
        }

        .btn{
            border-radius: 40px;
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

        function deleteRows() {
            var checkboxes = document.getElementsByName("delete[]");
            var selectedIds = [];

            // Get the selected row IDs
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    selectedIds.push(checkboxes[i].value);
                }
            }

            // Send AJAX request to delete rows from the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "teacher_delete_process.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "success") {
                        alert("Selected Rows Deleted Successfully!");
                        // Refresh the page after deletion
                        location.reload();
                    } else {
                        alert("Error deleting rows: " + xhr.responseText);
                    }
                }
            };
            xhr.send("ids=" + JSON.stringify(selectedIds));
        }
    </script>
</head>

<body>


<div class="page-breadcrumb py-2" style=" background: #212f3e;;
            box-shadow: 0px 4px 66px 0px rgba(0, 0, 0, 0.15);">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-3">
                <a href="coordinator.php" class="btn btn-lg text-center"><span><i class="fa fa-arrow-left fa-1.5x"></i></span> Go Back to Dashboard</a>

            </div>

            <div class="col-3 text-center">

                <h4 class="page-title op-5" style="color:white ;font-size:25px"></h4>
            </div>
            <div class="col-3">
                <div class="text-end upgrade-btn">

                    <form action="logout.php" method="POST" style="display: inline;">
                        <a href="logout.php"><button type="submit" name="logout" class="btn btn-primary text-white">Logout</button></a>
                    </form>

                    <a href="reset.php" class="btn btn-primary text-white" target="_blank">Reset Password</a>

                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row  my-3 ">

            <div class="col-12">


                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <!-- <input type="text" name="search_prn" placeholder="Search by PRN" value="<?php echo $searchPRN; ?>">
                    <input type="submit" value="Search"> -->
                    <!-- Search Form -->
                    <div class="input-group d-flex mb-3 ">
                        <div class="form-outline">
                            <input id="form1" class="form-control" type="text" name="search_email" placeholder="Search by E-Mail" value="<?php echo $searchEmail; ?>" style="border-radius: 10px 0px 0px 10px;" />
                        </div>
                        <button type="submit" class="btn btn-primary" value="Search" style="background:#D9D9D9;border:0;  border-radius: 0px 10px 10px 0px !important;">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_174_40)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.75 18C10.8334 18 11.9062 17.7866 12.9071 17.372C13.9081 16.9574 14.8175 16.3497 15.5836 15.5836C16.3497 14.8175 16.9574 13.9081 17.372 12.9071C17.7866 11.9062 18 10.8334 18 9.75C18 8.66659 17.7866 7.5938 17.372 6.59286C16.9574 5.59193 16.3497 4.68245 15.5836 3.91637C14.8175 3.15029 13.9081 2.5426 12.9071 2.12799C11.9062 1.71339 10.8334 1.5 9.75 1.5C7.56196 1.5 5.46354 2.36919 3.91637 3.91637C2.36919 5.46354 1.5 7.56196 1.5 9.75C1.5 11.938 2.36919 14.0365 3.91637 15.5836C5.46354 17.1308 7.56196 18 9.75 18ZM19.5 9.75C19.5 12.3359 18.4728 14.8158 16.6443 16.6443C14.8158 18.4728 12.3359 19.5 9.75 19.5C7.16414 19.5 4.68419 18.4728 2.85571 16.6443C1.02723 14.8158 0 12.3359 0 9.75C0 7.16414 1.02723 4.68419 2.85571 2.85571C4.68419 1.02723 7.16414 0 9.75 0C12.3359 0 14.8158 1.02723 16.6443 2.85571C18.4728 4.68419 19.5 7.16414 19.5 9.75Z" fill="black" />
                                        <path d="M15.5156 17.6127C15.5606 17.6727 15.6086 17.7297 15.6626 17.7852L21.4376 23.5602C21.7189 23.8416 22.1004 23.9998 22.4983 24C22.8963 24.0001 23.2779 23.8422 23.5594 23.5609C23.8408 23.2796 23.999 22.8981 23.9992 22.5002C23.9993 22.1023 23.8414 21.7206 23.5601 21.4392L17.7851 15.6642C17.7315 15.6099 17.6738 15.5597 17.6126 15.5142C17.0242 16.3165 16.3171 17.0246 15.5156 17.6142V17.6127Z" fill="black" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_174_40">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </i>

                        </button>
                    </div>


                </form>

             

            </div>

            <div class="col-12">

                <table class="c-table" id="data-table">
                    <thead class="c-table__header">
                        <tr>
                            <th class="c-table__col-label">ID</th>
                            <th class="c-table__col-label">Email</th>
                            <th class="c-table__col-label">Name</th>
                            <th class="c-table__col-label">Subject</th>
                            <th class="c-table__col-label">Academic Year</th>
                            <th class="c-table__col-label">Branch</th>
                            <th class="c-table__col-label">Class</th>
                            <th class="c-table__col-label">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="c-table__body">
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
                              <td class='c-table__cell'>$id</td>
                              <td class='c-table__cell'>$email</td>
                              <td class='c-table__cell'>$name</td>
                              <td class='c-table__cell'>$subject</td>
                              <td class='c-table__cell'>$acad_year</td>
                              <td class='c-table__cell'>$branch</td>
                              <td class='c-table__cell'>$class</td>
                              <td class='c-table__cell'><input type='checkbox' name='delete[]' value='$id'></td>
                            </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No entries found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

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

            
        </div>
        <button onclick="saveChanges()" class="btn btn-primary">Save Changes</button>
            <button onclick="deleteRows()" class="btn btn-primary">Delete Rows</button>
    </div>
</body>

</html>