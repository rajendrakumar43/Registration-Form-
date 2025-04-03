<?php
include("db.php");

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $sql_district = "SELECT * FROM `district` WHERE `s_id` = '$state_id'";
    $district_result = mysqli_query($conn, $sql_district);

    if ($district_result) {
        echo '<option value="">Select District</option>';
        while ($district_row = mysqli_fetch_assoc($district_result)) {
            echo "<option value='" . $district_row['district_id'] . "'>" . $district_row['district_name'] . "</option>";
        }
    }
}
?>



