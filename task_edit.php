<?php
include("db.php");

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Fetch existing task details
    $sql = "SELECT * FROM task WHERE task_id = '$task_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $task = $row['task_name'];
        $desc = $row['description'];
        $status = $row['status'];
    } else {
        echo "<script>alert('Task not found'); window.location.href = 'tasklist.php';</script>";
        exit();
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql_update = "UPDATE task SET task_name = '$task', description = '$desc', status = '$status' WHERE task_id = '$task_id'";


    if (mysqli_query($conn, $sql_update)) {
        echo "<script>
          alert('Task updated successfully');
          window.location.href = 'tasklist.php';
        </script>";
    } else {
        echo "<script>alert('Error updating task: " . mysqli_error($conn) . "');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container">
        <h3 class="text-center">Edit Task</h3>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="task" class="form-label">Task:</label>
                <textarea class="form-control" name="task" id="task" rows="2"><?php echo htmlspecialchars($task); ?></textarea>

                <label for="desc" class="form-label">Description:</label>
                <input type="text" class="form-control" id="desc" name="desc" value="<?php echo htmlspecialchars($desc); ?>">

                <label for="status" class="form-label">Status:</label>
                <select name="status" id="status" class="form-select">
                    <option value="Pending" <?php echo ($status == "Pending") ? 'selected' : ''; ?>>Pending</option>
                    <option value="Inprogress" <?php echo ($status == "Inprogress") ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo ($status == "Completed") ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Update Task</button>
            <a href="tasklist.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</body>

</html>