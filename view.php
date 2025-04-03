<?php
include("db.php");
session_start();
$user_id  = $_SESSION['id'];
if ($user_id) {

    $query = "SELECT signup.name, signup.contact, signup.address, signup.file, state.state_name, district.district_name, city.city_name,academic.board,academic.courses,academic.total_marks,academic.secured_marks,academic.percentage,academic.attachment 
    FROM signup 
    LEFT JOIN state ON signup.state = state.state_id 
    LEFT JOIN district ON state.state_id = district.s_id 
    LEFT JOIN city ON district.district_id = city.d_id 
    LEFT JOIN academic ON signup.id = academic.sign_id
    WHERE signup.id ='$user_id'";

    $result = mysqli_query($conn, $query);
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "<script>window.location.href='logout.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Details</title>
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

        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
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
        <h2 class="text-center text-warning">User Details</h2>
        <div class="card p-3">

            <div class="text-center">
                <img src="images/<?php echo $user_data['file']; ?>" class="profile-pic" alt="User Photo">
            </div>
            <div class="col-md-8">
                <h4><?php echo $user_data['name']; ?></h4>
                <p><strong>Contact:</strong> <?php echo $user_data['contact']; ?></p>
                <p><strong>Address:</strong> <?php echo $user_data['address']; ?></p>
                <p><strong>City:</strong> <?php echo $user_data['city_name']; ?></p>
                <p><strong>District:</strong> <?php echo $user_data['district_name']; ?></p>
                <p><strong>State:</strong> <?php echo $user_data['state_name']; ?></p>
            </div>

        </div>

        <h3 class="mt-4 text-warning">Academic Details</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Board</th>
                    <th>Course</th>
                    <th>Total Marks</th>
                    <th>Secured Marks</th>
                    <th>Percentage</th>
                    <th>Attachment</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <?php
                $academic_query = "SELECT * FROM academic WHERE sign_id = '$user_id'";
                $academic_result = mysqli_query($conn, $academic_query);
                while ($row = mysqli_fetch_assoc($academic_result)) {
                ?>
                    <tr>
                        <td><?php echo $row['board']; ?></td>
                        <td><?php echo $row['courses']; ?></td>
                        <td><?php echo $row['total_marks']; ?></td>
                        <td><?php echo $row['secured_marks']; ?></td>
                        <td><?php echo $row['percentage']; ?>%</td>
                        <td>
                            <?php if ($row['attachment']) { ?>
                                <a href="documents/<?php echo $row['attachment']; ?>" target="_blank">View </a>
                            <?php } else {
                                echo "No attachment";
                            } ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-primary">Back</a>
    </div>
</body>

</html>