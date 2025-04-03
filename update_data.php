<?php
include("db.php");

if (isset($_POST['type'])) {
    $type = $_POST['type'];
    $str = "";

    if ($type == "state") {
        $sql = "SELECT * FROM `state` ORDER BY state_name ASC";
        $query = mysqli_query($conn, $sql);
        $str .= "<option value=''>Select State</option>"; // Default option
        while ($row = mysqli_fetch_assoc($query)) {
            $str .= "<option value='{$row['state_id']}'>{$row['state_name']}</option>";
        }
    } elseif ($type == "districtdata" && isset($_POST['id'])) {
        $state_id = mysqli_real_escape_string($conn, $_POST['id']);
        $sql = "SELECT * FROM `district` WHERE `s_id` = '$state_id' ORDER BY district_name ASC";
        $query = mysqli_query($conn, $sql);
        $str .= "<option value=''>Select District</option>"; // Default option
        while ($row = mysqli_fetch_assoc($query)) {
            $str .= "<option value='{$row['district_id']}'>{$row['district_name']}</option>";
        }
    } elseif ($type == "citydata" && isset($_POST['id'])) {
        $district_id = mysqli_real_escape_string($conn, $_POST['id']);
        $sql = "SELECT * FROM `city` WHERE `d_id` = '$district_id' ORDER BY city_name ASC";
        $query = mysqli_query($conn, $sql);
        $str .= "<option value=''>Select City</option>"; // Default option
        while ($row = mysqli_fetch_assoc($query)) {
            $str .= "<option value='{$row['city_id']}'>{$row['city_name']}</option>";
        }
    }

    echo $str;
}
?>
