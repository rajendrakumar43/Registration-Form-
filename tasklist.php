<?php
include("db.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: index.php");
    exit();
}
$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
   
    $sql = "UPDATE task SET status = '$status' WHERE task_id = '$task_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "Status updated successfully";
    } else {
        echo "Error updating  status: " . mysqli_error($conn);
    }
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasklist</title>
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
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="dashboard.php" class="btn btn-danger me-2">Back</a>
            <a href="taskassign.php" class="btn btn-success me-2">Add</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Sl No.</th>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th style="width: 200px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM task WHERE user_id = '$user_id'";

                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr class="text-center">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['task_name'] ?></td>
                                <td><?php echo $row['description'] ?></td>
                                <td class="status_column"><?php echo $row['status'] ?></td>
                                <td class="d-flex align-items-center">
                                    <select name="status" class="form-select status_update" data-task-id="<?php echo $row['task_id']; ?>" <?php echo ($row['status'] == 'Completed') ? 'disabled' : ''; ?>>
                                        <option value="Pending" <?php echo ($row['status'] == "Pending") ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Inprogress" <?php echo ($row['status'] == "Inprogress") ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="Completed" <?php echo ($row['status'] == "Completed") ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                    <a href="<?php echo ($row['status'] == 'Completed') ? '#' : 'task_edit.php?id=' . $row['task_id']; ?>">
                                        <button class="btn btn-primary btn-sm <?php echo ($row['status'] == 'Completed') ? 'disabled' : ''; ?>" <?php echo ($row['status'] == 'Completed') ? 'disabled' : ''; ?>>Edit</button>
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".status_update").change(function() {
                var task_id = $(this).data("task-id");
                var status = $(this).val();
                // alert(status);
                var selectElement = $(this);
                $.ajax({
                    url: "update_status.php",
                    type: "POST",
                    data: {
                        task_id: task_id,
                        status: status
                    },
                    success: function(response) {
                        alert(response);
                        selectElement.closest("tr").find("td:nth-child(4)").text(status);
                        if (status === "Completed") {
                            selectElement.prop("disabled", true);
                            selectElement.closest("td").find("button").prop("disabled", true); // Disable Edit button
                        }
                    },
                    error: function() {
                        alert("Error updating status");
                    }
                });
            });
        });
    </script>
</body>

</html>