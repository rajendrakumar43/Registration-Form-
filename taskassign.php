<?php
include "db.php";
session_start();
$user_id = $_SESSION['id'];

if (isset($_POST['submit'])) {

    $task = $_POST['task'];
    $desc = $_POST['desc'];
    $status = $_POST['status'];

    $sql = "INSERT INTO task(user_id,task_name,description,status)VALUES('$user_id','$task','$desc','$status')";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>
          alert('data insert successfully');
          window.location.href = 'tasklist.php';
          </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Page</title>
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
        <form action="" method="POST" id="myform" class="mb-4">
        <a href="tasklist.php" class="btn btn-danger">Back</a>
            <div class="mb-3">
                <label for="task" class="form-label">Task:</label>
                <textarea class="form-control" name="task" id="task" rows="1"></textarea>
                <span class="error" id="taskerror"></span><br>
                <label for="desc" class="form-label">Description:</label>
                <input type="text" class="form-control" id="desc" name="desc">
                <span class="error" id="descerror"></span><br>
                <label for="status" class="form-label">Select Status :</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="Inprogress">In progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                <span class="statuserror" id="descerror"></span>   
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $("#myform").submit(function(event) {
            $(".error").text(""); // Clear previous errors
            let isValid = true;

            if ($("#task").val().trim() === "") {
                $("#taskerror").text("Task field is required.");
                isValid = false;
            } else if (/^\s/.test($("#task").val())) {
                $("#taskerror").text("Do not allow space at the beginning.");
                isValid = false;
            } else if (!/^[a-zA-Z\s]+$/.test($("#task").val())) {
                $("#taskerror").text("Only letters allowed.");
                isValid = false;
            }
            if ($("#desc").val().trim() === "") {
                $("#descerror").text("State field is required.");
                isValid = false;
            }
            else if (/^\s/.test($("#desc").val())) {
                $("#descerror").text("Do not allow space at the beginning.");
                isValid = false;
            } else if (!/^[a-zA-Z\s]+$/.test($("#desc").val())) {
                $("#descerror").text("Only letters allowed.");
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault(); // Prevent submission if errors
            }
        });
    </script>
</body>

</html>