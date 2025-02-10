<?php
// database connection
include 'includes/db_connection.php';

// Main query 
$query = "SELECT
    cl.checklist_id,
    st.student_number,
    st.student_fname,
    st.student_lname,
    st.student_Mname,
    st.student_email,
    st.student_address,
    st.student_program,
    cl.course_code,
    cr.course_title,
    cr.credit_unit_lecture,
    cr.credit_unit_laboratory,
    cr.contact_hours_lecture,
    cr.contact_hours_laboratory,
    cl.prerequisite_course,
    cl.credit_unit,
    cl.year_level,
    cl.semester,
    cl.instructor_id,
    ins.instructor_first_name,
    ins.instructor_last_name,
    cl.grade,
    cl.status
FROM
    Checklist cl
LEFT JOIN Student st ON cl.student_number = st.student_number
LEFT JOIN Course cr ON cl.course_code = cr.course_code
LEFT JOIN Instructor ins ON cl.instructor_id = ins.instructor_id ";


// Number of records per page
$records_per_page = 9;

// Default page number
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting record for the query
$start_from = ($page - 1) * $records_per_page;

// Initialize filter condition
$filter_condition = '';
if (isset($_GET['filter']) && $_GET['filter'] !== 'selectyr') {
    $selected_filter = $_GET['filter'];
    switch ($selected_filter) {
        case 'firstyear_firstsem':
            $filter_condition = " WHERE cl.year_level = 'First Year' AND cl.semester = 'First Semester'";
            break;
        case 'firstyear_secondsem':
            $filter_condition = " WHERE cl.year_level = 'First Year' AND cl.semester = 'Second Semester'";
            break;
        case 'secondyear_firstsem':
            $filter_condition = " WHERE cl.year_level = 'Second Year' AND cl.semester = 'First Semester'";
            break;
        case 'secondyear_secondsem':
            $filter_condition = " WHERE cl.year_level = 'Second Year' AND cl.semester = 'Second Semester'";
            break;
        case 'thirdyear_firstsem':
            $filter_condition = " WHERE cl.year_level = 'Third Year' AND cl.semester = 'First Semester'";
            break;
        case 'thirdyear_secondsem':
            $filter_condition = " WHERE cl.year_level = 'Third Year' AND cl.semester = 'Second Semester'";
            break;
        case 'thirdyear_midyear':
            $filter_condition = " WHERE cl.year_level = 'Third Year' AND cl.semester = 'Mid Year'";
            break;
        case 'fourthyear_firstsem':
            $filter_condition = " WHERE cl.year_level = 'Fourth Year' AND cl.semester = 'First Semester'";
            break;
        case 'fourthyear_secondsem':
            $filter_condition = " WHERE cl.year_level = 'Fourth Year' AND cl.semester = 'Second Semester'";
            break;
        default:
            break;
    }
}




// Query to get total number of records
$count_query = "SELECT COUNT(*) AS total_records FROM Checklist cl
                LEFT JOIN Student st ON cl.student_number = st.student_number"
    . $filter_condition;

// Execute the count query
$count_result = mysqli_query($conn, $count_query);

// Check for errors
if (!$count_result) {
    die("Count query failed: " . mysqli_error($conn));
}

$count_row = mysqli_fetch_assoc($count_result);

// Check if 'total_records' key exists in the result
if (isset($count_row['total_records'])) {
    $total_records = $count_row['total_records'];
} else {
    die("Failed to fetch total records count.");
}

$total_pages = ceil($total_records / $records_per_page);

$query .= $filter_condition;

// Add LIMIT clause to retrieve paginated results
$query .= " LIMIT $start_from, $records_per_page";

// Execute the main query
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$name_query = "SELECT student_fname, student_lname FROM Student WHERE student_number = '202211717'";
$name_result = mysqli_query($conn, $name_query);

if ($name_result && mysqli_num_rows($name_result) > 0) {
    $row = mysqli_fetch_assoc($name_result);
    $student_name = $row['student_fname'] . ' ' . $row['student_lname'];
} else {
    $student_name = 'Student';
}

// Free the result set
mysqli_free_result($name_result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Checklist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
    <link rel='stylesheet' href="css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<style>
    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: url("assets/backg.png");
        background-attachment: fixed;
        background-position: center;
        background-size: cover;

    }


    .select {
        /* margin-left: 2rem; */
    }
</style>

<body>
    <div class="container-fluid container">
        <div class="table" id="student_table">
            <section class="table__header">
                <div class="student_name">
                    <h3><span style="color: #006400;">Hello</span>, <?php echo htmlspecialchars($student_name); ?></h3>
                </div>
                <!-- Filter dropdown -->
                <div class="select">
                    <form method="GET" action="">
                        <select name="filter" id="filter" class="form-select">
                            <option value="selectyr" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'selectyr' ? 'selected' : ''; ?>>- Select Year - Semester -</option>
                            <option value="firstyear_firstsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'firstyear_firstsem' ? 'selected' : ''; ?>>First Year - First Semester</option>
                            <option value="firstyear_secondsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'firstyear_secondsem' ? 'selected' : ''; ?>>First Year - Second Semester</option>
                            <option value="secondyear_firstsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'secondyear_firstsem' ? 'selected' : ''; ?>>Second Year - First Semester</option>
                            <option value="secondyear_secondsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'secondyear_secondsem' ? 'selected' : ''; ?>>Second Year - Second Semester</option>
                            <option value="thirdyear_firstsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'thirdyear_firstsem' ? 'selected' : ''; ?>>Third Year - First Semester</option>
                            <option value="thirdyear_secondsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'thirdyear_secondsem' ? 'selected' : ''; ?>>Third Year - Second Semester</option>
                            <option value="thirdyear_midyear" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'thirdyear_midyear' ? 'selected' : ''; ?>>Third Year - Mid Year</option>
                            <option value="fourthyear_firstsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'fourthyear_firstsem' ? 'selected' : ''; ?>>Fourth Year - First Semester</option>
                            <option value="fourthyear_secondsem" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'fourthyear_secondsem' ? 'selected' : ''; ?>>Fourth Year - Second Semester</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">Filter Year & Sem</button>
                        <!-- <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" id="exportExcel">Export to Excel</a></li>
                            </ul>
                        </div> -->
                    </form>
                </div>


                <div class="search-box">
                    <button class="btn-search"><i class="fas fa-search"></i></button>
                    <input type="text" class="input-search" id="search" placeholder="Type to Search...">
                </div>



            </section>
            <section class="table__body">
                <table id="student_data">
                    <thead>
                        <tr>
                            <th style="background-color: rgb(155, 212, 155);">Course Code</th>
                            <th style="background-color: rgb(155, 212, 155);">Course Title</th>
                            <th style="background-color: rgb(155, 212, 155);">Credit Unit Lecture</th>
                            <th style="background-color: rgb(155, 212, 155);">Credit Unit Laboratory</th>
                            <th style="background-color: rgb(155, 212, 155);">Contact Hours Lecture</th>
                            <th style="background-color: rgb(155, 212, 155);">Contact Hours Laboratory</th>
                            <th style="background-color: rgb(155, 212, 155);">Pre-requisite Course</th>
                            <th style="background-color: rgb(155, 212, 155);">Year Level</th>
                            <th style="background-color: rgb(155, 212, 155);">Semester</th>
                            <th style="background-color: rgb(155, 212, 155);">Final Grade</th>
                            <th style="background-color: rgb(155, 212, 155);">Instructor</th>
                            <th style="background-color: rgb(155, 212, 155);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_grade_points = 0;
                        $total_credits = 0;
                        $total_credit_units_lecture = 0;
                        $total_credit_units_laboratory = 0;
                        $total_contact_hours_lecture = 0;
                        $total_contact_hours_laboratory = 0;

                        // Loop through the result set and populate table rows
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Determine the status based on the values in the row
                            if (is_null($row['grade']) && !is_null($row['instructor_id'])) {
                                $status = 'ENROLLED';
                            } elseif (is_null($row['grade']) && is_null($row['instructor_id'])) {
                                $status = 'UNENROLLED';
                            } elseif ($row['grade'] == 'S' || $row['grade'] <= 3) {
                                $status = 'PASSED';
                            } else {
                                $status = 'FAILED';
                            }

                            // Output the table row 
                            echo "<tr onclick='openModal({$row['checklist_id']}, \"{$row['grade']}\", \"{$row['instructor_id']}\", \"{$row['course_code']}\", \"{$row['course_title']}\")'>";
                            echo "<td>" . $row['course_code'] . "</td>";
                            echo "<td>" . $row['course_title'] . "</td>";
                            echo "<td>" . $row['credit_unit_lecture'] . "</td>";
                            echo "<td>" . $row['credit_unit_laboratory'] . "</td>";
                            echo "<td>" . $row['contact_hours_lecture'] . "</td>";
                            echo "<td>" . $row['contact_hours_laboratory'] . "</td>";
                            echo "<td>" . $row['prerequisite_course'] . "</td>";
                            echo "<td>" . $row['year_level'] . "</td>";
                            echo "<td>" . $row['semester'] . "</td>";
                            echo "<td>" . $row['grade'] . "</td>";
                            echo "<td>" . $row['instructor_first_name'] . " " . $row['instructor_last_name'] . "</td>";
                            echo "<td class='status " . strtolower($status) . "'>" . $status . "</td>";
                            echo "</tr>";

                            // Calculate grade 
                            $credit_units = $row['credit_unit_lecture'] + $row['credit_unit_laboratory'];
                            $grade_value = floatval(trim($row["grade"], 's'));

                            // Calculate total of credits and contact hrs
                            $total_credit_units_lecture += $row["credit_unit_lecture"];
                            $total_credit_units_laboratory += $row["credit_unit_laboratory"];
                            $total_contact_hours_lecture += $row["contact_hours_lecture"];
                            $total_contact_hours_laboratory += $row["contact_hours_laboratory"];

                            $total_grade_points += $credit_units * $grade_value;
                            $total_credits += $credit_units;
                        }

                        // Calculate GWA if filter condition is not empty
                        if (!empty($filter_condition)) {
                            if ($total_credits > 0) {
                                $gwa = $total_grade_points / $total_credits;
                                echo "<tr style='background-color:#ffff6;'>";
                                echo "<td></td>";
                                echo "<td><strong style='font-weight: 600'>Sub Total:</strong</td>";
                                echo "<td style='text-align: center;'>$total_credit_units_lecture</td>";
                                echo "<td>$total_credit_units_laboratory</td>";
                                echo "<td>$total_contact_hours_lecture</td>";
                                echo "<td>$total_contact_hours_laboratory</td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td><strong style='font-weight: 600'>GWA:</strong></td>";
                                echo "<td><strong style='color: green;font-weight: 600'>" . ($gwa > 0 ? number_format($gwa, 2) : '--') . "</strong></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "</tr>";
                            }
                        }
                        ?>

                    </tbody>
                </table>
                <br>
                <!-- Pagination -->
                <nav class="nav-page" aria-label="Page navigation example">
                    <ul class="pagination justify-content-end" id="pagination">
                        <?php
                        // Previous page link
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Previous</a></li>";
                        } else {
                            echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
                        }

                        // Page numbers
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo "<li class='page-item" . ($page == $i ? ' active' : '') . "'><a class='page-link' href='?page=" . $i . "'>$i</a></li>";
                        }

                        // Next page link
                        if ($page < $total_pages) {
                            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
                        } else {
                            echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
                        }
                        ?>
                    </ul>
                </nav>
            </section>
        </div>


        <!-- Bootstrap Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Grade & Instructor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="checklist_id" name="checklist_id">

                            <div class="mb-3">
                                <label for="course_code" class="form-label">Course Code:</label>
                                <input type="text" class="form-control" id="course_code" name="course_code" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="course_title" class="form-label">Course Title:</label>
                                <input type="text" class="form-control" id="course_title" name="course_title" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="grade" class="form-label">Final Grade:</label>
                                <input type="text" class="form-control" id="grade" name="grade">
                            </div>

                            <div class="mb-3">
                                <label for="instructor" class="form-label">Instructor:</label>
                                <select class="form-control" id="instructor" name="instructor">
                                    <option value="">Select Instructor</option>
                                    <?php
                                    // Fetch instructors from the database
                                    $instructorQuery = "SELECT instructor_id, instructor_first_name, instructor_last_name FROM Instructor";
                                    $instructorResult = mysqli_query($conn, $instructorQuery);

                                    while ($row = mysqli_fetch_assoc($instructorResult)) {
                                        echo "<option value='" . $row['instructor_id'] . "'>" . $row['instructor_id'] . " - " . $row['instructor_first_name'] . " " . $row['instructor_last_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <script>
        $(document).ready(function() {
            // Pagination click event
            $(document).on("click", ".pagination a", function(event) {
                event.preventDefault();
                var page = $(this).attr("href").split("page=")[1];
                load_data(page);
            });

            // Function to load data with pagination
            function load_data(page) {
                $.ajax({
                    url: "pagination.php",
                    method: "GET",
                    data: {
                        page: page
                    },
                    success: function(data) {
                        $("#student_data tbody").html(data);
                    },
                });
            }
            load_data();

            // Search functionality
            $("#search").keyup(function() {
                var query = $(this).val();
                $.ajax({
                    url: "search.php",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $("#student_data tbody").html(data);
                    },
                });
            });

            // Open modal and fill data
            window.openModal = function(id, grade, instructor, courseCode, courseTitle) {
                $("#checklist_id").val(id);
                $("#grade").val(grade);
                $("#instructor").val(instructor);
                $("#course_code").val(courseCode);
                $("#course_title").val(courseTitle);
                $("#editModal").modal("show");
            };


            $("#editForm").submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                var checklist_id = $("#checklist_id").val();
                var grade = $("#grade").val().trim();
                var instructor = $("#instructor").val().trim();

                // Create an object to send data (even if it's empty)
                var data = {
                    checklist_id: checklist_id
                };

                data.grade = grade === "" ? "" : grade; // Send empty string if blank
                data.instructor = instructor === "" ? "" : instructor; // Send empty string if blank

                $.ajax({
                    url: "update.php",
                    method: "POST",
                    data: data,
                    success: function(response) {
                        if (response.trim() == "success") {
                            alert("Record updated successfully!");
                            $("#editModal").modal("hide");
                            location.reload(); // Reload table after update
                        } else {
                            alert("Error updating record: " + response);
                        }
                    },
                    error: function() {
                        alert("AJAX error: Could not update record.");
                    }
                });
            });



            // Function to export table data to Excel
            $("#exportExcel").click(function() {
                var wb = XLSX.utils.book_new();
                wb.Props = {
                    Title: "Student Checklist",
                    Author: "Your Name",
                    CreatedDate: new Date(),
                };

                wb.SheetNames.push("Student Data");
                var ws_data = [
                    [
                        "Course Code",
                        "Course Title",
                        "Credit Unit Lecture",
                        "Credit Unit Laboratory",
                        "Contact Hours Lecture",
                        "Contact Hours Laboratory",
                        "Pre-requisite Course",
                        "Year Level",
                        "Semester",
                        "Final Grade",
                        "Instructor",
                        "Status",
                    ],
                ];

                // Fetch data from table
                $("#student_data tbody tr").each(function(row, tr) {
                    var rowData = [];
                    $(tr)
                        .find("td")
                        .each(function(col, td) {
                            rowData.push($(td).text());
                        });
                    ws_data.push(rowData);
                });

                var ws = XLSX.utils.aoa_to_sheet(ws_data);
                wb.Sheets["Student Data"] = ws;

                var wbout = XLSX.write(wb, {
                    bookType: "xlsx",
                    type: "binary"
                });

                function s2ab(s) {
                    var buf = new ArrayBuffer(s.length);
                    var view = new Uint8Array(buf);
                    for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
                    return buf;
                }
                saveAs(
                    new Blob([s2ab(wbout)], {
                        type: "application/octet-stream"
                    }),
                    "student_checklist.xlsx"
                );
            });
        });
    </script>

</body>

</html>