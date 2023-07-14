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
        /* General styles */

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

        button {}

        .form {
            border-radius: 10px;
        }

        p {
            color: #DFDFDF;
        }
    </style>
    <!-- <link rel="icon" type="image/png" sizes="16x16" href="./Coordinator Dashboard css and js /assets/images/favicon.png"> -->
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

    <!-- Include Footer CSS -->
    <link rel="stylesheet" href="./css/footer_style.css">

    
</head>

<body>

    <a class="float" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fa fa-question-circle my-float"></i>
    </a>

    <div class="modal fade" id="uploadModal">
        <div class="modal-dialog">
            
            <div class="modal-content " style="background-color:#202E39;border-radius: 1rem">
    
                <div class="modal-header" style="color:white">
                    <h5 class="modal-title" id="uploadModalLabel">Instructions to edit data </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:white"></button>
                </div>
                <div class="modal-body">
    
                   <ul style="color: white;">
                     <li>Choose the cell you want to edit</li>
                     <li>Click on the cell till you see text cursor</li>
                     <li>You can delete and write data in that cell</li>
                     <li>After changes click on the Save Button to save changes in the database</li>
                   </ul>
    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
    
            
    
    
                </div>
            </div>
        </div>

    </div>





    <div class="container ">

        <div class="row d-flex my-3 ">
            <div class="div col-6" style="align-self:left">

                <form method="GET" action="" class=" ">
                    <!-- <input type="text" name="search_prn" placeholder="Search by PRN" value="<?php echo $searchPRN; ?>">
                    <input type="submit" value="Search"> -->
                    <!-- Search Form -->
                    <div class="input-group d-flex ">
                        <div class="form-outline">
                            <input id="form1" class="form-control" type="text" name="search_prn" placeholder="Search by PRN" value="<?php echo $searchPRN; ?>" style="border-radius: 10px 0px 0px 10px;" />
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
            <br />
            <!-- Delete Form -->
            <div class="div col-6  style=" align-self:right">

                <form method="POST" action="" class="">

                    <div class="input-group justify-content-end">
                        <div class="form-outline">
                            <input id="form1" class="form-control" type="text" name="delete_prn" placeholder="Enter PRN(s) to delete" style="border-radius: 10px 0px 0px 10px;" />
                        </div>
                        <button type="submit" class="btn " value="Delete" style="background:#D9D9D9;border:0;  border-radius: 0px 10px 10px 0px !important;">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M8.25 8.25C8.44891 8.25 8.63968 8.32902 8.78033 8.46967C8.92098 8.61032 9 8.80109 9 9V18C9 18.1989 8.92098 18.3897 8.78033 18.5303C8.63968 18.671 8.44891 18.75 8.25 18.75C8.05109 18.75 7.86032 18.671 7.71967 18.5303C7.57902 18.3897 7.5 18.1989 7.5 18V9C7.5 8.80109 7.57902 8.61032 7.71967 8.46967C7.86032 8.32902 8.05109 8.25 8.25 8.25ZM12 8.25C12.1989 8.25 12.3897 8.32902 12.5303 8.46967C12.671 8.61032 12.75 8.80109 12.75 9V18C12.75 18.1989 12.671 18.3897 12.5303 18.5303C12.3897 18.671 12.1989 18.75 12 18.75C11.8011 18.75 11.6103 18.671 11.4697 18.5303C11.329 18.3897 11.25 18.1989 11.25 18V9C11.25 8.80109 11.329 8.61032 11.4697 8.46967C11.6103 8.32902 11.8011 8.25 12 8.25ZM16.5 9C16.5 8.80109 16.421 8.61032 16.2803 8.46967C16.1397 8.32902 15.9489 8.25 15.75 8.25C15.5511 8.25 15.3603 8.32902 15.2197 8.46967C15.079 8.61032 15 8.80109 15 9V18C15 18.1989 15.079 18.3897 15.2197 18.5303C15.3603 18.671 15.5511 18.75 15.75 18.75C15.9489 18.75 16.1397 18.671 16.2803 18.5303C16.421 18.3897 16.5 18.1989 16.5 18V9Z" fill="#212529" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.75 4.5C21.75 4.89782 21.592 5.27936 21.3107 5.56066C21.0294 5.84196 20.6478 6 20.25 6H19.5V19.5C19.5 20.2956 19.1839 21.0587 18.6213 21.6213C18.0587 22.1839 17.2956 22.5 16.5 22.5H7.5C6.70435 22.5 5.94129 22.1839 5.37868 21.6213C4.81607 21.0587 4.5 20.2956 4.5 19.5V6H3.75C3.35218 6 2.97064 5.84196 2.68934 5.56066C2.40804 5.27936 2.25 4.89782 2.25 4.5V3C2.25 2.60218 2.40804 2.22064 2.68934 1.93934C2.97064 1.65804 3.35218 1.5 3.75 1.5H9C9 1.10218 9.15804 0.720644 9.43934 0.43934C9.72064 0.158035 10.1022 0 10.5 0H13.5C13.8978 0 14.2794 0.158035 14.5607 0.43934C14.842 0.720644 15 1.10218 15 1.5H20.25C20.6478 1.5 21.0294 1.65804 21.3107 1.93934C21.592 2.22064 21.75 2.60218 21.75 3V4.5ZM6.177 6L6 6.0885V19.5C6 19.8978 6.15804 20.2794 6.43934 20.5607C6.72064 20.842 7.10218 21 7.5 21H16.5C16.8978 21 17.2794 20.842 17.5607 20.5607C17.842 20.2794 18 19.8978 18 19.5V6.0885L17.823 6H6.177ZM3.75 4.5V3H20.25V4.5H3.75Z" fill="#212529" />
                                </svg>
                            </i>
                        </button>


                    </div>

                </form>
            </div>


        </div>


    </div>



    <?php
    // Pagination
    $entriesPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * $entriesPerPage;

    // Query to fetch entries from the database
    $query = "SELECT * FROM $branch_student WHERE prn LIKE '%$searchPRN%' LIMIT $start, $entriesPerPage";
    $result = $conn->query($query);
    ?>

    <div class="container">
        <table class="c-table" id="data-table">
            <thead class="c-table__header">
                <tr>
                    <th class="c-table__col-label">ID</th>
                    <th class="c-table__col-label">PRN</th>
                    <th class="c-table__col-label">Name</th>
                    <th class="c-table__col-label">Email</th>
                    <th class="c-table__col-label">Open</th>
                    <th class="c-table__col-label">General</th>
                    <th class="c-table__col-label">Acad Year</th>
                    <th class="c-table__col-label">Branch</th>
                    <th class="c-table__col-label">Class</th>
                    <th class="c-table__col-label">Semester</th>
                    <th class="c-table__col-label">Current Year</th>
                </tr>
            </thead>
            <tbody class="c-table__body">
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
                          <td class='c-table__cell'>$id</td>
                          <td class='c-table__cell'>$prn</td>
                          <td class='c-table__cell'>$name</td>
                          <td class='c-table__cell'>$email</td>
                          <td class='c-table__cell'>$open</td>
                          <td class='c-table__cell'>$general</td>
                          <td class='c-table__cell'>$acad_year</td>
                          <td class='c-table__cell'>$branch</td>
                          <td class='c-table__cell'>$class</td>
                          <td class='c-table__cell'>$semester</td>
                          <td class='c-table__cell'>$crnt_year</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No entries found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="wrapper">
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

            <button onclick="saveChanges()" class="py-2 px-3" style="border:0;  border-radius: 20px !important;background-color:#3D8BFD;color:white;">Save Changes</button>



            <script>
                (function() {
                    var tableHeaders = document.getElementsByClassName("c-table__header");
                    var tableCells = document.getElementsByClassName("c-table__cell");
                    var span = document.createElement("span");

                    for (var i = 0; i < tableCells.length; i++) {
                        span = document.createElement("span");
                        span.classList.add("c-table__label");
                        tableCells[i].prepend(span);
                    }

                    var tableLabels = tableHeaders[0].getElementsByClassName("c-table__col-label");
                    var spanMod = document.getElementsByClassName("c-table__label");

                    for (var i = 0; i < tableLabels.length; i++) {
                        for (var a = 0; a < tableCells.length; a++) {
                            spanMod[a].innerHTML = tableLabels[i].innerHTML;
                        }
                    }

                    var b = tableLabels.length;
                    for (var a = 0; a < tableCells.length; a++) {
                        spanMod[a].innerHTML = tableLabels[a % b].innerHTML;
                    }
                })();
            </script>


            <!-- jQuery -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

            <!-- Bootstrap JavaScript -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>


            <script src="./Coordinator Dashboard css and js/assets/libs/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap tether Core JavaScript -->
            <script src="./Coordinator Dashboard css and js/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="./Coordinator Dashboard css and js/dist/js/app-style-switcher.js"></script>
            <!--Wave Effects -->
            <script src="./Coordinator Dashboard css and js/dist/js/waves.js"></script>
            <!--Menu sidebar -->
            <script src="./Coordinator Dashboard css and js/dist/js/sidebarmenu.js"></script>
            <!--Custom JavaScript -->
            <script src="./Coordinator Dashboard css and js/dist/js/custom.js"></script>
            <!--This page JavaScript -->
            <!--chartis chart-->
            <script src="./Coordinator Dashboard css and js/assets/libs/chartist/dist/chartist.min.js"></script>
            <script src="./Coordinator Dashboard css and js/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
            <script src="./Coordinator Dashboard css and js/dist/js/pages/dashboards/dashboard1.js"></script>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>