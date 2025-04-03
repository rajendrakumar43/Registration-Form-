<?php
include("db.php");
if (isset($_POST["submit"])) {
    if (isset($_POST["sname"]) && !empty($_POST["sname"])) {

        $sname = mysqli_real_escape_string($conn, $_POST["sname"]);

        $sql = "INSERT INTO `state`(`state_name`) VALUES ('$sname')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
                alert('Data inserted successfully');
                window.location.href = 'state.php';
                </script>";
        } else {
            echo "<script>alert('duplicate inserting data');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>State record</title>
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

    <div class="container mb-3 ">
        <!-- <h4 class="text-center mb-4" class="mb-4">Enter State Information</h4> -->
        <form action="" method="POST" id="myform" class="mb-4">
            <div class="mb-3">
                <label for="sname" class="form-label">State Name:</label>
                <input type="text" class="form-control" id="sname" name="sname">
                <span class="error" id="snameerror"></span>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <h4 class="text-center text-warning">State List</h4>
    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>State Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM `state`";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row["state_name"] ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#myform").submit(function(event) {
                $(".error").text(""); // Clear previous errors
                let isValid = true;



                if ($("#sname").val().trim() === "") {
                    $("#snameerror").text(" state field is required :");
                    isValid = false;
                } else if (/^\s/.test($("#sname").val())) {
                    $("#snameerror").text("do not allow space");
                    isValid = false;
                } else if (!/^[a-zA-Z\s]+$/.test($("#sname").val())) {
                    $("#snameerror").text("Only letters allowed");
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