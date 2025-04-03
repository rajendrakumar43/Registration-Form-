<?php
include("db.php");

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $academic_sql = "SELECT * FROM academic WHERE sign_id = '$user_id'";
    $academic_result = mysqli_query($conn, $academic_sql);

    $location_sql = "SELECT signup.name, signup.contact, signup.address, signup.file, signup.user_name, signup.password,
       state.state_name,state.state_id, district.district_name, district.district_id, city.city_id, city.city_name,
       academic.board, academic.courses, academic.total_marks, academic.secured_marks, academic.percentage, academic.attachment
        FROM signup 
        LEFT JOIN state ON signup.state = state.state_id 
        LEFT JOIN district ON signup.dist = district.district_id
        LEFT JOIN city ON signup.city = city.city_id 
        LEFT JOIN academic ON signup.id = academic.sign_id
        WHERE signup.id = '$user_id'";

    $location_result = mysqli_query($conn, $location_sql);
    $location_data = mysqli_fetch_assoc($location_result);
}

if (isset($_POST['update'])) {
    //  echo "<pre>";
    //  print_r($_POST); 
    // print_r($_FILES); 
    //  echo "</pre>";
    //  exit();
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $password = !empty($_POST['password']) ? $_POST['password'] : $location_data['password'];

    // Handle Image Upload
    $filename = $location_data['file'];
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $target_dir = "images/";
        $filename = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    }


    // Update signup table
    $update_sql = "UPDATE signup SET name='$name', contact='$contact', address='$address', user_name='$username',  state='$state', dist='$district', city='$city', file='$filename' WHERE id='$user_id'";
    mysqli_query($conn, $update_sql);
    // exit();
    // password='$password',

    // Update academic details
    if (!empty($_POST['board'])) {
        foreach ($_POST['board'] as $key => $board) {
            $course = $_POST['course'][$key];
            $total_marks = $_POST['total_marks'][$key];
            $secured_marks = $_POST['secured_marks'][$key];
            $percentage = $_POST['percentage'][$key];
            $academic_id = isset($_POST['academic_id'][$key]) ?$_POST['academic_id'][$key]: "";
            $pdf_filename = isset($_POST['existing_attachment'][$key]) ? $_POST['existing_attachment'][$key] : "";

            if (isset($_FILES["attachment"]["name"][$key]) && $_FILES["attachment"]["error"][$key] == 0) {
                $pdf_filename = basename($_FILES["attachment"]["name"][$key]);
                move_uploaded_file($_FILES["attachment"]["tmp_name"][$key], "documents/" . $pdf_filename);
            }

            $check_sql = "SELECT id FROM academic WHERE sign_id='$user_id' AND board='$board' AND courses='$course'";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                $academic_update_sql = "UPDATE academic SET board='$board', courses='$course', total_marks='$total_marks', 
                secured_marks='$secured_marks', percentage='$percentage', attachment='$pdf_filename' WHERE id='$academic_id' AND sign_id='$user_id'";
            } else {
                $academic_update_sql = "INSERT INTO academic (sign_id, board, courses, total_marks, secured_marks, percentage, attachment) 
                VALUES ('$user_id', '$board', '$course', '$total_marks', '$secured_marks', '$percentage', '$pdf_filename')";
            }
            mysqli_query($conn, $academic_update_sql);
        }
    }

    echo "<script>alert('User updated successfully'); window.location.href = 'dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(30, 123, 160);
        }

        .navbar {
            background-color: rgb(17, 30, 44) !important;
            padding: 10px 20px;
        }

        .navbar-brand {
            color: rgb(37, 193, 221);
        }

        .brand:hover {
            color: #ffc107;
        }

        .nav-link {
            color: #ffffff !important;
            margin-left: 2rem;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-outline-success {
            color: #ffffff;
            border-color: #ffc107;
        }

        .btn-outline-success:hover {
            background-color: #ffc107;
            color: #343a40;
        }

        
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mt-1">
        <div class="container-fluid">
            <a class="navbar-brand brand" href="#">WebSite</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Contact</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
   
    <div class="container-sm p-4" style="max-width: 800px; margin: auto;">
        <a href="dashboard.php" class="btn btn-danger">Back</a>
        <h2 class="text-center text-warning">Update User</h2>
        <form class="row g-3" method="post" enctype="multipart/form-data">
            <div class="col-md-6">
                <label class="form-label">Name :</label>
                <input type="text" class="form-control" name="name" value="<?php echo $location_data['name']; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact :</label>
                <input type="tel" class="form-control" name="contact" value="<?php echo $location_data['contact']; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Address :</label>
                <input type="text" class="form-control" name="address" value="<?php echo $location_data['address']; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Photo :</label>
                <input type="file" class="form-control" name="file">
                <img src="images/<?php echo $location_data['file']; ?>" width="100">
            </div>
            <div class="col-md-6">
                <label class="form-label">Username :</label>
                <input type="text" class="form-control" name="username" value="<?php echo $location_data['user_name']; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Password :</label>
                <input type="password" class="form-control" name="password" value="<?php echo $location_data['password']; ?>">
            </div>
            <div class="col-md-4">
                <label>State:</label>
                <select name="state" class="form-select" id="state">
                    <option value="<?php echo $location_data['state_id']; ?>" selected><?php echo $location_data['state_name']; ?></option>
                </select>
            </div>
            <div class="col-md-4">
                <label>District:</label>
                <select name="district" class="form-select" id="district">
                    <option value="<?php echo $location_data['district_id']; ?>" selected><?php echo $location_data['district_name']; ?></option>
                </select>
            </div>
            <div class="col-md-4">
                <label>City:</label>
                <select name="city" class="form-select" id="city">
                    <option value="<?php echo $location_data['city_id']; ?>" selected><?php echo $location_data['city_name']; ?></option>
                </select>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-warning">Academic Details</h4>
                <button type="button" class="btn btn-success btn-sm add_btn">Add</button>
            </div>
            <table class="table table-bordered" id="academic_table">
                <thead>
                    <tr>
                        <th>Board</th>
                        <th>Course</th>
                        <th>Total Marks</th>
                        <th>Secured Marks</th>
                        <th>Percentage</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($academic_result)) { ?>
                        <tr>
                            <td>
                                <input type="hidden" name="academic_id[]" value="<?php echo $row['id']; ?>">
                                <input type="text" class="form-control" name="board[]" value="<?php echo $row['board']; ?>">
                            </td>
                            <td>
                                <select class="form-select course" name="course[]" style="width: 85px;">
                                    <option value="" disabled>Select Course</option>
                                    <option value="10th" <?php if ($row['courses'] == "10th") echo "selected"; ?>>10th</option>
                                    <option value="+2Sc" <?php if ($row['courses'] == "+2Sc") echo "selected"; ?>>+2Sc</option>
                                    <option value="+3Sc" <?php if ($row['courses'] == "+3Sc") echo "selected"; ?>>+3Sc</option>
                                    <option value="Pg" <?php if ($row['courses'] == "Pg") echo "selected"; ?>>Pg</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control totalmark" name="total_marks[]"  value="<?php echo $row['total_marks']; ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control securemark" name="secured_marks[]" style="width: 70px;" value="<?php echo $row['secured_marks']; ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control percentage" name="percentage[]" style="width: 75px;" value="<?php echo $row['percentage']; ?>">
                            </td>
                            <td>
                                <input type="file" class="form-control attachment" name="attachment[]">
                                <?php if (!empty($row['attachment'])) { ?>
                                    <a href="documents/<?php echo $row['attachment']; ?>" target="_blank">View</a>
                                    <input type="hidden" name="existing_attachment[]" value="<?php echo $row['attachment']; ?>">
                                <?php } ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row" data-id="<?php echo $row['id']; ?>">Remove</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="col-12">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadData(type, category_id = "", selected_value = "") {
                $.ajax({
                    url: "update_data.php",
                    type: "POST",
                    data: {
                        type: type,
                        id: category_id
                    },
                    success: function(data) {
                        if (type === "state") {
                            $("#state").html(data);

                            // Wait for state data to load before selecting the default value
                            $("#state").val("<?php echo $location_data['state_id']; ?>").trigger("change");
                        } else if (type === "districtdata") {
                            $("#district").html(data);

                            // Set default district after data is loaded
                            $("#district").val("<?php echo $location_data['district_id']; ?>").trigger("change");
                        } else if (type === "citydata") {
                            $("#city").html(data);

                            // Set default city after data is loaded
                            $("#city").val("<?php echo $location_data['city_id']; ?>");
                        }
                    }
                });
            }

            // Load states first, and wait for response
            loadData("state");

            // When state changes, load districts
            $("#state").on("change", function() {
                var state = $(this).val();
                if (state !== "") {
                    loadData("districtdata", state);
                } else {
                    $("#district").html('<option value="">Select District</option>');
                    $("#city").html('<option value="">Select City</option>');
                }
            });

            // When district changes, load cities
            $("#district").on("change", function() {
                var district = $(this).val();
                if (district !== "") {
                    loadData("citydata", district);
                } else {
                    $("#city").html('<option value="">Select City</option>');
                }
            });



            function calculatePercentage(row) {
                let totalMarks = parseFloat($(row).find(".totalmark").val()) || 0;
                let securedMarks = parseFloat($(row).find(".securemark").val()) || 0;

                if (totalMarks > 0) {
                    let percentage = ((securedMarks / totalMarks) * 100).toFixed(2);
                    $(row).find(".percentage").val(percentage + "%");
                } else {
                    $(row).find(".percentage").val(""); // Clear if invalid input
                }
            }

            function isDuplicateEntry(board, course) {
                let isDuplicate = false;
                $("#academic_table tbody tr").each(function() {
                    let existingBoard = $(this).find("input[name='board[]']").val();
                    let existingCourse = $(this).find("select[name='course[]']").val();

                    if (existingBoard === board && existingCourse === course) {
                        isDuplicate = true;
                        return false; // Break loop if duplicate found
                    }
                });
                return isDuplicate;
            }
            $("form").on("submit", function(event) {
                let isValid = true;
                let selectedPairs = new Set();

                $("#academic_table tbody tr").each(function() {
                    let board = $(this).find("input[name='board[]']").val();
                    let course = $(this).find("select[name='course[]']").val();
                    let pair = board + "-" + course;

                    if (selectedPairs.has(pair)) {
                        alert("Duplicate entry detected!");
                        isValid = false;
                        return false; // Stop checking further
                    }
                    selectedPairs.add(pair);
                });

                if (!isValid) {
                    event.preventDefault(); // Stop form submission
                }
            });

            // Calculate percentage on input change
            $(document).on("keyup change", ".securemark, .totalmark", function() {
                var row = $(this).closest("tr");
                calculatePercentage(row);
            });


           
            var rowTemplate = `<tr>
                                        <td><input type="text" class="form-control" name="board[]"></td>
                                        <td>
                                            <select class="form-select course" name="course[]">
                                                <option value="" >Select Course</option>
                                                <option value="10th">10th</option>
                                                <option value="+2Sc">+2Sc</option>
                                                <option value="+3Sc">+3Sc</option>
                                                <option value="Pg">Pg</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control totalmark" name="total_marks[]"></td>
                                        <td><input type="text" class="form-control securemark" name="secured_marks[]"></td>
                                        <td><input type="text" class="form-control percentage" name="percentage[]"></td>
                                        <td><input type="file" class="form-control" name="attachment[]"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                    </tr>`;

            $(document).on("click", ".remove-row", function() {
                var row = $(this).closest("tr");
                var academicId = $(this).data("id"); // Get academic record ID

                if (confirm("Are you sure you want to delete this record?")) {
                    if (academicId) {
                        $.ajax({
                            url: "delete_academic.php",
                            type: "POST",
                            data: {
                                id: academicId
                            },
                            success: function(response) {
                                if (response.trim() === "success") {
                                    row.remove(); // Remove row after successful deletion
                                    checkIfTableEmpty();
                                } else {
                                    alert("Error deleting record. Try again.");
                                }
                            }
                        });
                    } else {
                        row.remove(); // Remove row if it has no associated ID (new row)
                        checkIfTableEmpty();
                    }
                }
            });

            $(document).on("click", ".add_btn", function() {
                $("#academic_table tbody").append(rowTemplate);
            });

            function checkIfTableEmpty() {
                if ($("#academic_table tbody tr").length === 0) {
                    $("#academic_table tbody").append(rowTemplate);
                }
            }
            // });

        });
        $(document).ready(function() {
            function validateRow(row) {
                let isValid = true;

                // Board validation
                let board = row.find("[name='board[]']");
                if (board.val().trim() === "") {
                    board.addClass("is-invalid");
                    isValid = false;
                } else {
                    board.removeClass("is-invalid");
                }

                // Course validation
                let course = row.find("[name='course[]']");
                if (course.val() === "") {
                    course.addClass("is-invalid");
                    isValid = false;
                } else {
                    course.removeClass("is-invalid");
                }

                // Total Marks validation
                let totalMarks = row.find("[name='total_marks[]']");
                if (totalMarks.val() === "" || totalMarks.val() <= 0) {
                    totalMarks.addClass("is-invalid");
                    isValid = false;
                } else {
                    totalMarks.removeClass("is-invalid");
                }

                // Secured Marks validation
                let securedMarks = row.find("[name='secured_marks[]']");
                if (securedMarks.val() === "" || securedMarks.val() < 0 || parseFloat(securedMarks.val()) > parseFloat(totalMarks.val())) {
                    securedMarks.addClass("is-invalid");
                    isValid = false;
                } else {
                    securedMarks.removeClass("is-invalid");
                }

                // Percentage calculation (Read-only)
                let percentage = row.find("[name='percentage[]']");
                if (totalMarks.val() && securedMarks.val()) {
                    let percent = (parseFloat(securedMarks.val()) / parseFloat(totalMarks.val())) * 100;
                    percentage.val(percent.toFixed(2) + "%");
                } else {
                    percentage.val("");
                }

                // Attachment validation
                let attachment = row.find("[name='attachment[]']");
                if (attachment.val()) {
                    let ext = attachment.val().split('.').pop().toLowerCase();
                    if (!["pdf", "jpg", "jpeg", "png"].includes(ext)) {
                        attachment.addClass("is-invalid");
                        isValid = false;
                    } else {
                        attachment.removeClass("is-invalid");
                    }
                }

                return isValid;
            }

            // Validate on input change
            $(document).on("input change", "[name='board[]'], [name='course[]'], [name='total_marks[]'], [name='secured_marks[]'], [name='attachment[]']", function() {
                validateRow($(this).closest("tr"));
            });

            // Prevent form submission if validation fails
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

            // Auto calculate percentage when secured marks change
            $(document).on("input", "[name='secured_marks[]'], [name='total_marks[]']", function() {
                let row = $(this).closest("tr");
                let total = parseFloat(row.find("[name='total_marks[]']").val()) || 0;
                let secured = parseFloat(row.find("[name='secured_marks[]']").val()) || 0;
                let percentage = row.find("[name='percentage[]']");

                if (secured > total) {
                    row.find("[name='secured_marks[]']").addClass("is-invalid");
                    percentage.val("");
                } else {
                    row.find("[name='secured_marks[]']").removeClass("is-invalid");
                    percentage.val(((secured / total) * 100).toFixed(2) + "%");
                }
            });

            // Remove row event
            $(document).on("click", ".remove-row", function() {
                $(this).closest("tr").remove();
            });
        });
    </script>


</body>

</html>