<?php
session_start();
include("db.php"); // Ensure this file connects to your database

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    echo "<script>alert('No User ID provided!'); window.location.href='index.php';</script>";
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $old_psw = md5($_POST['old_psw']);
    $new_psw = md5($_POST['new_psw']);
    $conf_psw = md5($_POST['conf_psw']);

    // Fetch current password from the database
    $query = "SELECT password FROM signup WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "<script>alert('User not found!'); window.location.href='index.php';</script>";
        exit();
    }

    $db_password = $row['password'];

    // Verify old password
    if ($old_psw !== $db_password) {
        echo "<script>alert('Old password is incorrect!');</script>";
    } elseif ($new_psw !== $conf_psw) {
        echo "<script>alert('New password and Confirm password do not match!');</script>";
    } else {
        // Update the password without hashing
        $update_query = "UPDATE signup SET password='$new_psw' WHERE id='$user_id'";

        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Password changed successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error updating password!');</script>";
        }
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

        .login-container h5 {
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
            <button class="btn btn-sm btn-danger"><a href="dashboard.php" style="color: white;
            text-decoration: none;">Back</a></button>
            <h5 class="text-center mb-4">Change Password</h5>
            <form action="" method="POST" id="myform">
                <div class="mb-3">
                    <label for="old_psw" class="form-label">Old Password :</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="old_psw" name="old_psw" placeholder="Enter old password">
                        <button class="btn btn-outline-secondary togglePassword" type="button" >show</button>
                    </div>
                    <span class="error" id="old_pswerror"></span>
                </div>
                <div class="mb-3">
                    <label for="new_psw" class="form-label">New Password :</label>
                    <input type="password" class="form-control" id="new_psw" name="new_psw" placeholder="Enter new password">
                    <span class="error" id="new_pswerror"></span>
                </div>
                <div class="mb-3">
                    <label for="conf_psw" class="form-label">Confirm Password :</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="conf_psw" name="conf_psw" placeholder="Enter confirm password">
                        <button class="btn btn-outline-secondary togglePassword" type="button">show</button>
                    </div>
                    <span class="error" id="conf_pswerror"></span>
                </div>

                <button type="submit" name="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#myform").submit(function(event) {
                $(".error").text(""); // Clear previous errors
                let isValid = true;



                if ($("#old_psw").val().trim() === "") {
                    $("#old_pswerror").text("old password is required :");
                    isValid = false;
                } else if (/^\s/.test($("#old_psw").val())) {
                    $("#old_pswerror").text("do not allow space");
                    isValid = false;
                }
                if ($("#new_psw").val().trim() === "") {
                    $("#new_pswerror").text(" new password is required :");
                    isValid = false;
                } else if (/^\s/.test($("#new_psw").val())) {
                    $("#new_pswerror").text("do not allow space");
                    isValid = false;
                }
                if ($("#conf_psw").val().trim() === "") {
                    $("#conf_pswerror").text(" confirm password is required :");
                    isValid = false;
                } else if (/^\s/.test($("#conf_psw").val())) {
                    $("#conf_pswerror").text("do not allow space");
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent submission if errors
                }
            });
            $(document).ready(function() {
                $('.togglePassword').on('click', function() {
                    let passwordField = $(this).prev('input');
                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        $(this).text('Hide');
                    } else {
                        passwordField.attr('type', 'password');
                        $(this).text('Show');
                    }
                });
            });

        });
    </script>
</body>

</html>