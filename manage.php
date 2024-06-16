<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "module1";

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error");
}

$sql = "SELECT * FROM register";
$result = $data->query($sql);

$data->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="veh_app.css">
    <title>Manage Student</title>
</head>
<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>FKPark</p>
            </div>
            <div class="nav-button">
                <a href="login.php"><button class="btn white-btn" id="logoutBtn">Log Out</button></a>
            </div>
        </nav>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h4>Manage Student
                            <a href="admin.php" class="btn btn-secondary float-end me-2">BACK</a>
                            </h4>   
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
                                unset($_SESSION['message']);
                            }
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Student Name</th>
                                        <th>Matric ID</th>
                                        <th>Student Level</th> <!-- Added this line -->
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>".$count."</td>";
                                            echo "<td>".$row['StudName']."</td>";
                                            echo "<td>".$row['MatricID']."</td>";
                                            echo "<td>".$row['StudLevel']."</td>"; // Added this line
                                            echo "<td>".$row['StudEmail']."</td>";
                                            echo "<td>".$row['StudHPnum']."</td>";
                                            echo '<td>
                                                    <form id="deleteForm_'.$row['id'].'" action="deleteprofile.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="id" value="'.$row['id'].'">
                                                        <button type="button" onclick="confirmDelete('.$row['id'].')" class="btn btn-danger">Delete</button>
                                                    </form>
                                                  </td>';
                                            echo "</tr>";
                                            $count++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No Record Found!</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this profile?")) {
                // If user confirms, submit the form with the delete action
                document.getElementById('deleteForm_' + id).submit();
            }
        }
    </script>
</body>
</html>
