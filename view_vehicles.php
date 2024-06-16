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

$sql = "SELECT * FROM vehicle";
$result = $data->query($sql);
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
    <title>View Vehicle</title>
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
                            <h4>My Vehicle
                                <a href="student.php" class="btn btn-secondary float-end me-2">BACK</a>
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
                                        <th>Edit</th>
                                        <th>Status</th>
                                        <th>QR Code</th>
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
                                            echo "<td><a href='".$row['Grant']."' target='_blank'>View Grant</a></td>";
                                            echo '<td>
                                                    <form action="edit_vehicle.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="platenum" value="'.$row['Platenum'].'">
                                                        <button type="submit" class="btn btn-warning">Edit</button>
                                                    </form>
                                                  </td>';
                                            echo "<td>".$row['status']."</td>";
                                            if ($row['status'] == 'Approved') {
                                                echo '<td>
                                                        <form action="generate_qr.php" method="post" style="display:inline;">
                                                            <input type="hidden" name="platenum" value="'.$row['Platenum'].'">
                                                            <button type="submit" class="btn btn-success">Generate</button>
                                                        </form>
                                                      </td>';
                                            } else {
                                                echo '<td><button class="btn btn-secondary" disabled>Generate QR</button></td>';
                                            }
                                            echo "</tr>";
                                            $count++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No Record Found!</td></tr>";
                                    }
                                    $data->close();
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
