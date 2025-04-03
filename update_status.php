<?php
include("db.php");
if (isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    
    $upd_task_sql = "UPDATE task SET status = '$status' WHERE task_id = '$task_id'";
    if (mysqli_query($conn, $upd_task_sql)) {
        echo "Status updated successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
