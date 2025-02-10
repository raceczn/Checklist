<?php
// Include database connection
include 'includes/db_connection.php';

// Check if POST request is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checklist_id = $_POST['checklist_id'];
    $grade = isset($_POST['grade']) ? trim($_POST['grade']) : null;
    $instructor = isset($_POST['instructor']) ? trim($_POST['instructor']) : null;

    // Validate input
    if (!empty($checklist_id)) {
        $fields = [];
        $params = [];
        $types = "";

        if ($grade !== null) {
            if ($grade === "") {
                $fields[] = "grade = NULL";
            } else {
                $fields[] = "grade = ?";
                $params[] = $grade;
                $types .= "s";
            }
        }

        if ($instructor !== null) {
            if ($instructor === "") {
                $fields[] = "instructor_id = NULL";
            } else {
                $fields[] = "instructor_id = ?";
                $params[] = $instructor;
                $types .= "s";
            }
        }

        if (!empty($fields)) {
            $params[] = $checklist_id;
            $types .= "i";

            $sql = "UPDATE Checklist SET " . implode(", ", $fields) . " WHERE checklist_id = ?";
            $stmt = $conn->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "error: No fields provided for update.";
        }
    } else {
        echo "error: Missing checklist_id.";
    }
}

$conn->close();
?>
