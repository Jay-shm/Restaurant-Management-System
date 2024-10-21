<?php
include '../includes/database.php';

if (isset($_POST['staff_id'])) {
    $staff_id = $_POST['staff_id'];

    // Fetch staff details from the database
    $stmt = $conn->prepare("SELECT fname, lname, email, mobile, role FROM staff WHERE id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff_data = $result->fetch_assoc();

    // Return the staff details as JSON
    echo json_encode($staff_data);
}
?>
