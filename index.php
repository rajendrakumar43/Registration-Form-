<?php
session_start();
include("db.php");
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); //md5
    $query = "SELECT * FROM signup WHERE user_name='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result); // Fetch the user data
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $username;
        echo "<script>alert('Login successful!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Invalid username or password!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-container h3 {
            color: rgb(13, 51, 173);
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .forget_password {
            margin-left: 75px;
            text-decoration: none;
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
    <div class="container">
        <div class="login-container">
            <h3 class="text-center mb-4">Login</h3>
            <form action="" method="POST" id="myform">
                <div class="mb-3">
                    <label for="username" class="form-label">UserName</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
                    <span class="error" id="usererror"></span>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                    <span class="error" id="passerror"></span>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                    <a href="" class="forget_password">Forgot password ?</a>
                </div>

                <button type="submit" name="submit" class="btn btn-success">Login</button>
                <div class="register-link">
                    <p>Don't have an account? <a href="registration.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#myform").submit(function(event) {
                $(".error").text(""); // Clear previous errors
                let isValid = true;



                if ($("#username").val().trim() === "") {
                    $("#usererror").text(" Username is required :");
                    isValid = false;
                } else if (/^\s/.test($("#username").val())) {
                    $("#usererror").text("do not allow space");
                    isValid = false;
                } else if (!/^[a-zA-Z\s]+$/.test($("#username").val())) {
                    $("#usererror").text("Only letters allowed");
                    isValid = false;
                }
                if ($("#password").val().trim() === "") {
                    $("#passerror").text(" Password is required :");
                    isValid = false;
                }else if (/^\s/.test($("#password").val())) {
                    $("#passerror").text("do not allow space");
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