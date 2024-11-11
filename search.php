<?php
// database connection
include 'includes/db_connection.php';

$output = '';

if (isset($_POST['query'])) {
  $search = mysqli_real_escape_string($conn, $_POST['query']);

  // SQL query for search
  $query = "SELECT 
                cl.course_code,
                cl.prerequisite_course,
                cl.year_level,
                cl.semester,
                cl.grade,
                st.student_fname,
                st.student_lname,
                st.student_Mname,
                st.student_email,
                st.student_address,
                st.student_program,
                cr.course_title,
                cr.credit_unit_lecture,
                cr.credit_unit_laboratory,
                cr.contact_hours_lecture,
                cr.contact_hours_laboratory,
                ins.instructor_first_name,
                ins.instructor_last_name
              FROM 
                Checklist cl
              LEFT JOIN 
                Student st ON cl.student_number = st.student_number
              LEFT JOIN 
                Course cr ON cl.course_code = cr.course_code
              LEFT JOIN 
                Instructor ins ON cl.instructor_id = ins.instructor_id
              WHERE 
                CONCAT_WS(' ', cl.course_code, cl.grade, st.student_fname, st.student_lname, st.student_Mname,
                          st.student_email, st.student_address, st.student_program,  cr.credit_unit_lecture,
                          cr.credit_unit_laboratory, cr.contact_hours_lecture, cr.contact_hours_laboratory, cr.course_title, ins.instructor_first_name, ins.instructor_last_name, cl.year_level, cl.semester, cl.status) LIKE '%$search%'";

  $result = mysqli_query($conn, $query);

  function highlight($text, $search) {
    return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<span class="highlight">$1</span>', $text);
  }

  if ($result) {
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        if ($row['instructor_first_name'] !== null && $row['instructor_last_name'] !== null && $row['grade'] === null) {
          $statusClass = 'enrolled';
          $statusText = 'ENROLLED';
        } else {
          // status based on grade
          if ($row['grade'] === null || (int)$row['grade'] > 3) {
            $statusClass = 'unenrolled';
            $statusText = 'UNENROLLED';
          } else {
            $statusClass = 'passed';
            $statusText = 'PASSED';
          }
        }

        $output .= "
                    <tr>
                        <td>" . highlight($row['course_code'], $search) . "</td>
                        <td>" . highlight($row['course_title'], $search) . "</td>
                        <td>" . highlight($row['credit_unit_lecture'], $search) . "</td>
                        <td>" . highlight($row['credit_unit_laboratory'], $search) . "</td>
                        <td>" . highlight($row['contact_hours_lecture'], $search) . "</td>
                        <td>" . highlight($row['contact_hours_laboratory'], $search) . "</td>
                        <td>" . highlight($row['prerequisite_course'], $search) . "</td>
                        <td>" . highlight($row['year_level'], $search) . "</td>
                        <td>" . highlight($row['semester'], $search) . "</td>
                        <td>" . highlight($row['grade'], $search) . "</td>
                        <td>" . highlight($row['instructor_first_name'] . ' ' . $row['instructor_last_name'], $search) . "</td>
                        <td class='status {$statusClass}'>{$statusText}</td>
                    </tr>";
      }
    } else {
      $output .= "<tr><td colspan='12' style='text-align: center;'>No records found</td></tr>";
    }
  } else {
    $output .= "<tr><td colspan='12'>Error executing query</td></tr>";
  }

  echo $output;
}
?>

