<?php
include("db.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);  //md5
    $confirm_password = md5($_POST['confirm_password']); //md5
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // File Upload Handling for Image (JPG, PNG)
    $target_dir = "images/";
    $filename = "";

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $file_type = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        if (!in_array($file_type, $allowed_types)) {
            echo "<script>alert('Only JPG and PNG files are allowed.'); window.history.back();</script>";
            exit();
        }

        $filename = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "<script>alert('Error uploading image file.'); window.history.back();</script>";
            exit();
        }
    }

    // Check if username already exists
    $sql = "SELECT id FROM signup WHERE user_name = '$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username already exists!'); window.history.back();</script>";
        exit();
    }

    // Insert into signup table
    $ins_sql = "INSERT INTO signup (name, contact, address, file, user_name, password, state, dist, city) 
                VALUES ('$name', '$contact', '$address', '$filename', '$username', '$password', '$state', '$district', '$city')";
    if (mysqli_query($conn, $ins_sql)) {
        $sign_id = mysqli_insert_id($conn); // Get last inserted ID

        // Academic Data Handling
        if (!empty($_POST['board'])) {
            foreach ($_POST['board'] as $key => $board) {
                $course = $_POST['course'][$key];
                $total_marks = $_POST['total_marks'][$key];
                $secured_marks = $_POST['secured_marks'][$key];
                $percentage = $_POST['percentage'][$key];

                $pdf_filename = "";
                $image_filename = "";
                $allowed_image_types = ["jpg", "jpeg", "png"];
                $allowed_pdf_types = ["pdf"];

                if (isset($_FILES["attachment"]["name"][$key]) && $_FILES["attachment"]["error"][$key] == 0) {
                    $file_type = strtolower(pathinfo($_FILES["attachment"]["name"][$key], PATHINFO_EXTENSION));

                    if (!in_array($file_type, array_merge($allowed_pdf_types, $allowed_image_types))) {
                        echo "<script>alert('Only PDF, JPG, JPEG, and PNG files are allowed for attachment.'); window.history.back();</script>";
                        exit();
                    }

                    $filename = basename($_FILES["attachment"]["name"][$key]);
                    $target_dir = "documents/";

                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    $target_file = $target_dir . $filename;

                    if (!move_uploaded_file($_FILES["attachment"]["tmp_name"][$key], $target_file)) {
                        echo "<script>alert('Error uploading file.'); window.history.back();</script>";
                        exit();
                    }

                    // Store file names separately for PDFs and images
                    if (in_array($file_type, $allowed_pdf_types)) {
                        $pdf_filename = $filename;
                    } elseif (in_array($file_type, $allowed_image_types)) {
                        $image_filename = $filename;
                    }
                }
                $attachment_filename = !empty($pdf_filename) ? $pdf_filename : $image_filename;


                // Insert into academic table
                $academic_sql = "INSERT INTO academic (sign_id, board, courses, total_marks, secured_marks, percentage, attachment) 
                                VALUES ('$sign_id', '$board', '$course', '$total_marks', '$secured_marks', '$percentage', '$attachment_filename')";

                mysqli_query($conn, $academic_sql);
            }
        }

        echo "<script>alert('Registration successful'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(30, 123, 160);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            overflow-y: auto;
        }

        .form-container {
            max-width: 900px;
            width: 90%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-control {
            height: 40px;
            font-size: 14px;
        }

        .form-select {
            height: 40px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h4 class="text-center">USER REGISTRATION</h4>
        <form class="row g-3" method="post" action="" enctype="multipart/form-data" id="myform">
            <div class="col-md-6">
                <label for="name" class="form-label">Name :</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Username">
                <span class="error" id="nameerror"></span>
            </div>
            <div class="col-md-6">
                <label for="contact" class="form-label">Contact :</label>
                <input type="tel" class="form-control" id="contact" name="contact" placeholder="Enter Mobile Number">
                <span class="error" id="contacterror"></span>
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address :</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address">
                <span class="error" id="addresserror"></span>
            </div>
            <div class="col-md-6">
                <label for="file" class="form-label">Photo :</label>
                <input type="file" class="form-control" name="file" id="file">
                <span class="error" id="fileerror"></span>
            </div>
            <div class="col-md-4">
                <label for="state" class="form-label">State :</label>
                <select id="state" class="form-select" name="state">
                    <?php
                    $state_sql = "SELECT * FROM state";
                    $state_result = mysqli_query($conn, $state_sql);
                    if ($state_result) {
                        while ($state_row = mysqli_fetch_assoc($state_result)) {
                    ?>
                            <option value="<?php echo  $state_row['state_id'] ?>">
                                <?php echo  $state_row['state_name'] ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <span class="error" id="stateerror"></span>
            </div>
            <div class="col-md-4">
                <label for="district" class="form-label">District :</label>
                <select id="district" class="form-select" name="district">
                    <option selected>Choose...</option>
                </select>
                <span class="error" id="districterror"></span>
            </div>
            <div class="col-md-4">
                <label for="city" class="form-label">City :</label>
                <select id="city" class="form-select" name="city">
                    <option selected>Choose...</option>
                </select>
                <span class="error" id="cityerror"></span>
            </div>
            <div class="container mt-4">
                <h4 class="text-center">Academic Details</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="academic_table">
                        <thead class="table-white">
                            <tr>
                                <th>Board</th>
                                <th>Course</th>
                                <th>Total Mark</th>
                                <th>Secured Mark</th>
                                <th>Percentage</th>
                                <th>Attachment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control board" name="board[]"> </td>
                                <td>
                                    <select class="form-select course " name="course[]" style="width: 112px;">
                                        <option selected>Choose...</option>
                                        <option>10th</option>
                                        <option>+2Sc </option>
                                        <option>+3Sc</option>
                                        <option>Pg</option>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control totalmark" name="total_marks[]" style="width: 112px;"></td>
                                <td><input type="number" class="form-control securemark" name="secured_marks[]" style="width: 112px;"></td>
                                <td><input type="text" class="form-control percentage" name="percentage[]"></td>
                                <td><input type="file" class="form-control attachment" name="attachment[]"></td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm add_btn">Add</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <h4>Login</h4>
            <div class="col-md-6">
                <label for="username" class="form-label">User Name :</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
                <span class="error" id="usererror"></span>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password :</label>
                <input type="tel" class="form-control" id="password" name="password" placeholder="Enter Password">
                <span class="error" id="passerror"></span>
            </div>
            <div class="col-md-6">
                <label for="confirmpassword" class="form-label">Confirm Password :</label>
                <input type="text" class="form-control" id="confirmpassword" name="confirm_password" placeholder="Enter Confirm Password">
                <span class="error" id="conpasserror"></span>
            </div>

            <div class="col-12">
                <button type="submit" name="submit" class="btn btn-primary">Sign Up</button>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                function loadData(type, category_id = "") {
                    $.ajax({
                        url: "get_data.php",
                        type: "POST",
                        data: {
                            type: type,
                            id: category_id
                        },
                        success: function(data) {
                            if (type == "citydata") {
                                $("#city").html(data);
                            } else if (type === "districtdata") {
                                $("#district").html(data);
                            } else if (type === "state") {
                                $("#state").html(data);
                            }
                        }
                    });
                }

                loadData("state"); // Load state data initially

                $("#state").on("change", function() {
                    var state = $(this).val();
                    if (state !== "") {
                        loadData("districtdata", state);
                    } else {
                        $("#district").html('<option value="">Select District</option>');
                    }
                });

                $("#district").on("change", function() {
                    var district = $(this).val();
                    if (district !== "") {
                        loadData("citydata", district);
                    } else {
                        $("#city").html('<option value="">Select City</option>');
                    }
                });

                function calculatePercentage(row) {
                    let totalMarks = parseFloat(row.find(".totalmark").val()) || 0;
                    let securedMarks = parseFloat(row.find(".securemark").val()) || 0;

                    if (securedMarks > totalMarks) {
                        alert("Secured Marks cannot be greater than Total Marks!");
                        row.find(".securemark").val("");
                        row.find(".percentage").val("");
                        return;
                    }

                    if (totalMarks > 0 && securedMarks >= 0) {
                        let percentage = (securedMarks / totalMarks) * 100;
                        row.find(".percentage").val(percentage.toFixed(2) + "%");
                    } else {
                        row.find(".percentage").val("");
                    }
                }

                $(document).on("input", ".totalmark, .securemark", function() {
                    let row = $(this).closest("tr");
                    calculatePercentage(row);
                });

                $(document).on("click", ".add_btn", function() {
                    let row = $(this).closest("tr").clone();

                    row.find("input, select").val(""); // Clear values
                    row.find(".course").val("Choose..."); // Ensure course resets to "Choose..."
                    row.find(".add_btn").removeClass("btn-success add_btn").addClass("btn-danger remove_btn").text("Remove");

                    $("#academic_table tbody").append(row);
                });

                $(document).on("click", ".remove_btn", function() {
                    $(this).closest("tr").remove();
                });

                function checkDuplicateSelection() {
                    let selectedValues = new Set();
                    let duplicateFound = false;

                    $("#academic_table tbody tr").each(function() {
                        let board = $(this).find(".board").val().trim();
                        let course = $(this).find(".course").val();
                        let combination = board + "|" + course;

                        if (selectedValues.has(combination)) {
                            duplicateFound = true;
                          
                            $(this).find(".course").val("Choose..."); // Reset duplicate entry
                            return false; // Exit loop early
                        }
                        selectedValues.add(combination);
                    });

                    return !duplicateFound;
                }

                $(document).on("change", ".board, .course", function() {
                    checkDuplicateSelection();
                });

                $(document).on("click", ".add_btn", function() {
                    let newRow = $(this).closest("tr").clone();

                    // Clear values
                    newRow.find("input, select").val("");
                    newRow.find(".course").val("Choose...");

                    // Change button class and text
                    newRow.find(".add_btn").removeClass("btn-success add_btn").addClass("btn-danger remove_btn").text("Remove");

                    // Append new row
                    $("#academic_table tbody").append(newRow);

                    // Check for duplicate after adding
                    if (!checkDuplicateSelection()) {
                        newRow.remove(); // Remove if it's a duplicate
                    }
                });

                $(document).on("click", ".remove_btn", function() {
                    $(this).closest("tr").remove();
                });

                $("form").submit(function(e) {
                    if (!checkDuplicateSelection()) {
                        // alert("Please correct duplicate entries before submitting.");
                        e.preventDefault(); // Prevent form submission if duplicate found
                    }
                });

                $("#myform").submit(function(event) {
                    $(".error").text("");
                    let isValid = true;

                    if ($("#name").val().trim() === "") {
                        $("#nameerror").text("Name is required");
                        isValid = false;
                    } else if (/^\s/.test($("#name").val())) {
                        $("#nameerror").text("Do not allow space");
                        isValid = false;
                    } else if (!/^[a-zA-Z\s]+$/.test($("#name").val())) {
                        $("#nameerror").text("Only letters allowed");
                        isValid = false;
                    }

                    if ($("#contact").val().trim() === "") {
                        $("#contacterror").text("Phone number is required");
                        isValid = false;
                    } else if (!/^\d{10}$/.test($("#contact").val())) {
                        $("#contacterror").text("Phone number must be 10 digits");
                        isValid = false;
                    }

                    if ($("#address").val().trim() === "") {
                        $("#addresserror").text("Address is required");
                        isValid = false;
                    } else if (/^\s/.test($("#address").val())) {
                        $("#addresserror").text("Do not allow space");
                        isValid = false;
                    }

                    if ($("#file").val() === "") {
                        $("#fileerror").text("File is required");
                        isValid = false;
                    }

                    if ($("#state").val().trim() === "") {
                        $("#stateerror").text("State field is required.");
                        isValid = false;
                    }

                    if ($("#district").val().trim() === "") {
                        $("#districterror").text("District field is required.");
                        isValid = false;
                    }

                    if ($("#city").val().trim() === "") {
                        $("#cityerror").text("City is required");
                        isValid = false;
                    }

                    if ($("#username").val().trim() === "") {
                        $("#usererror").text("Username is required");
                        isValid = false;
                    } else if (/^\s/.test($("#username").val())) {
                        $("#usererror").text("Do not allow space");
                        isValid = false;
                    } 
                    // else if (!/^[a-zA-Z\s]+$/.test($("#username").val())) {
                    //     $("#usererror").text("Only letters allowed");
                    //     isValid = false;
                    // }

                    if ($("#password").val().trim() === "") {
                        $("#passerror").text("Password is required");
                        isValid = false;
                    } else if (/^\s/.test($("#password").val())) {
                        $("#passerror").text("Do not allow space");
                        isValid = false;
                    }

                    if ($("#confirmpassword").val().trim() === "") {
                        $("#conpasserror").text("Confirm Password is required");
                        isValid = false;
                    } else if (/^\s/.test($("#confirmpassword").val())) {
                        $("#conpasserror").text("Do not allow space");
                        isValid = false;
                    }

                    if (!isValid) {
                        event.preventDefault();
                    }
                });

                function validateRow(row) {
                    let isValid = true;

                    let board = row.find(".board");
                    if (board.val().trim() === "") {
                        board.addClass("is-invalid");
                        isValid = false;
                    } else {
                        board.removeClass("is-invalid");
                    }

                    let course = row.find(".course");
                    if (course.val() === "Choose...") {
                        course.addClass("is-invalid");
                        isValid = false;
                    } else {
                        course.removeClass("is-invalid");
                    }

                    let totalMarks = row.find(".totalmark");
                    if (totalMarks.val() === "" || totalMarks.val() <= 0) {
                        totalMarks.addClass("is-invalid");
                        isValid = false;
                    } else {
                        totalMarks.removeClass("is-invalid");
                    }

                    let securedMarks = row.find(".securemark");
                    if (securedMarks.val() === "" || securedMarks.val() < 0 || parseFloat(securedMarks.val()) > parseFloat(totalMarks.val())) {
                        securedMarks.addClass("is-invalid");
                        isValid = false;
                    } else {
                        securedMarks.removeClass("is-invalid");
                    }

                    let percentage = row.find(".percentage");
                    if (totalMarks.val() && securedMarks.val()) {
                        let percent = (parseFloat(securedMarks.val()) / parseFloat(totalMarks.val())) * 100;
                        percentage.val(percent.toFixed(2) + "%");
                    } else {
                        percentage.val("");
                    }

                    let attachment = row.find(".attachment");
                    let file = attachment.val();
                    if (file) {
                        let ext = file.split('.').pop().toLowerCase();
                        let allowedExtensions = ["pdf", "jpg", "jpeg", "png"];

                        if (!allowedExtensions.includes(ext)) {
                            attachment.addClass("is-invalid");
                            isValid = false;
                        } else {
                            attachment.removeClass("is-invalid");
                        }
                    }
                    return isValid;
                }

                $(document).on("input change", ".board, .course, .totalmark, .securemark, .attachment", function() {
                    validateRow($(this).closest("tr"));
                });

                $("form").submit(function(e) {
                    let allValid = true;
                    $("#academic_table tbody tr").each(function() {
                        if (!validateRow($(this))) {
                            allValid = false;
                        }
                    });

                    if (!allValid) {
                        e.preventDefault();
                        alert("Please correct the errors before submitting.");
                    }
                });
            });
        </script>
    </div>
</body>

</html>