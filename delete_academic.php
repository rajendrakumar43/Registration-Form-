<?php
include("db.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Delete the academic record
    $delete_sql = "DELETE FROM academic WHERE id = '$id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "success"; // Send success response
    } else {
        echo "error"; // Send error response
    }
}
?>


