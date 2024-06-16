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

// Handle approve or reject button click
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $platenum = $_POST['platenum'];
        $update_sql = "UPDATE vehicle SET status='Approved' WHERE Platenum='$platenum'";
        if ($data->query($update_sql) === TRUE) {
            $_SESSION['message'] = "Vehicle with plate number $platenum has been approved.";
        } else {
            $_SESSION['message'] = "Error: " . $data->error;
        }
    } elseif (isset($_POST['reject'])) {
        $platenum = $_POST['platenum'];
        $update_sql = "UPDATE vehicle SET status='Rejected' WHERE Platenum='$platenum'";
        if ($data->query($update_sql) === TRUE) {
            $_SESSION['message'] = "Vehicle with plate number $platenum has been rejected.";
        } else {
            $_SESSION['message'] = "Error: " . $data->error;
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT * FROM vehicle WHERE status NOT IN ('Approved', 'Rejected')"; // Filter out approved or rejected vehicles
$result = $data->query($sql);

$data->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="veh_app.css">
    <title>Vehicle Approval</title>
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
                            <h4>Vehicle Approval
                            <a href="guard.php" class="btn btn-secondary float-end me-2">BACK</a>
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
                                        <th>Vehicle Type</th>
                                        <th>Plate Number</th>
                                        <th>Grant</th>
                                        <th>View</th>
                                        <th>Approve</th>
                                        <th>Reject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>".$count."</td>";
                                            echo "<td>".$row['VehicleType']."</td>";
                                            echo "<td>".$row['Platenum']."</td>";
                                            echo "<td>".$row['Grant']."</td>";
                                            echo '<td><a href="' . $row['Grant'] . '" class="btn btn-info" target="_blank">View</a></td>'; // Modified this line
                                            echo '<td>
                                                    <form action="" method="post" style="display:inline;">
                                                        <input type="hidden" name="platenum" value="'.$row['Platenum'].'">
                                                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                                    </form>
                                                  </td>';
                                            echo '<td>
                                                    <form action="" method="post" style="display:inline;">
                                                        <input type="hidden" name="platenum" value="'.$row['Platenum'].'">
                                                        <button type="submit" name="reject" class="btn btn-danger">Reject</button>
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
</body>
</html>