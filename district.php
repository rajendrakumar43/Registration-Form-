<?php
include("db.php");

if (isset($_POST['submit'])) {
    if (isset($_POST['sid']) && isset($_POST['dname'])) {
        $sid = ($_POST['sid']);
        $dname = ($_POST["dname"]);

        if (!empty($sid) && !empty($dname)) {

            $check_sql = "SELECT * FROM `district` WHERE `s_id` = '$sid' AND `district_name` = '$dname'";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<script>alert('Duplicate data entry');
                exit();
                </script>";
            } else {

                $sql = "INSERT INTO `district`(`s_id`, `district_name`) VALUES ('$sid', '$dname')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<script>
                            alert('Data inserted successfully');
                            window.location.href = 'district.php';
                          </script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            }
        } else {
            echo "<script> alert('all feilds are required.')</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>district record</title>
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

        

        .container {
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }

        table {
            max-width: 500px;
            margin: auto;
        }

        .error {
            color: red;
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
    <div class="container mb-3">
        <form action="" method="POST" id="myform" class="mb-4">
            <div class="mb-3">
                <label for="" class="form-label">Select Your State :</label>
                <select name="sid" id="state" class="form-select">
                    <option value="">Select State</option>
                    <?php
                    $sql_state = "SELECT * FROM `state`";
                    $state_result = mysqli_query($conn, $sql_state);
                    if ($state_result) {
                        while ($state_row = mysqli_fetch_assoc($state_result)) {
                    ?>
                            <option value="<?php echo $state_row['state_id'] ?>">
                                <?php echo $state_row['state_name'] ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <span id="stateError" class="error"></span><br>
                <label for="dname" class="form-label">Enter your District: </label>
                <input type="text" class="form-control" id="dname" name="dname">
                <span id="districtError" class="error"></span>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <h4 class="text-center text-warning">State and District List</h4>
    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>Sl No.</th>
                <th>State Name</th>
                <th>District Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_district = "SELECT district.district_id,district.district_name,state.state_name
                                    FROM district
                                    inner join state on district.s_id = state.state_id order by district.district_id desc";
            $district_result = mysqli_query($conn, $sql_district);
            if ($district_result) {
                $i = 1;
                while ($district_row = mysqli_fetch_assoc($district_result)) {
            ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $district_row["state_name"] ?></td>
                        <td><?php echo $district_row["district_name"] ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#myform").submit(function(event) {
                $(".error").text(""); // Clear previous errors
                let isValid = true;



                if ($("#state").val().trim() === "") {
                    $("#stateError").text(" state field is required :");
                    isValid = false;
                }
                if ($("#dname").val().trim() === "") {
                    $("#districtError").text(" district field is required :");
                    isValid = false
                } else if (/^\s/.test($("#dname").val())) {
                    $("#districtError").text("do not allow space");
                    isValid = false;
                } else if (!/^[a-zA-Z\s]+$/.test($("#dname").val())) {
                    $("#districtError").text("Only letters allowed");
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent submission if errors
                }
            });
        });
    </script>
</body>

</html>