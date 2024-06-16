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

// Fetch total number of students
$total_students_query = "SELECT COUNT(*) as total_students FROM register";
$total_students_result = mysqli_query($data, $total_students_query);
$total_students_row = mysqli_fetch_assoc($total_students_result);
$total_students = $total_students_row['total_students'];

// Fetch total number of undergraduate students
$total_undergraduate_query = "SELECT COUNT(*) as total_undergraduate FROM register WHERE StudLevel='undergraduate'";
$total_undergraduate_result = mysqli_query($data, $total_undergraduate_query);
$total_undergraduate_row = mysqli_fetch_assoc($total_undergraduate_result);
$total_undergraduate = $total_undergraduate_row['total_undergraduate'];

// Fetch total number of postgraduate students
$total_postgraduate_query = "SELECT COUNT(*) as total_postgraduate FROM register WHERE StudLevel='postgraduate'";
$total_postgraduate_result = mysqli_query($data, $total_postgraduate_query);
$total_postgraduate_row = mysqli_fetch_assoc($total_postgraduate_result);
$total_postgraduate = $total_postgraduate_row['total_postgraduate'];

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
    <link rel="stylesheet" href="base.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .content {
            margin: 20px;
        }
        .stats-box, .chart-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .stats-box h2, .chart-box h2 {
            margin-bottom: 20px;
        }
        .stats-box p {
            font-size: 18px;
            margin: 10px 0;
        }
        .chart-box {
            height: 400px;
        }
    </style>
    <title>Admin Report</title>
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
        <div class="side-bar">
            <div class="menu">
                <div class="item"><a href="admin_profile.php">Profile</a></div>
                <div class="item"><a href="reg_stud.php">Registration</a></div>
                <div class="item"><a href="#">Parking</a></div>
                <div class="item"><a href="#">Booking</a></div>
                <div class="item"><a href="#">Summon</a></div>
                <div class="item"><a href="#">Report</a></div>
            </div>
        </div>
        <div class="content">
            <div class="stats-box">
                <h2>Student Statistics</h2>
                <p>Total Students: <?php echo $total_students; ?></p>
                <p>Total Undergraduate Students: <?php echo $total_undergraduate; ?></p>
                <p>Total Postgraduate Students: <?php echo $total_postgraduate; ?></p>
            </div>
            <div class="chart-box">
                <h2>Student Distribution Chart</h2>
                <canvas id="studentChart"></canvas>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const ctx = document.getElementById('studentChart').getContext('2d');
            const studentChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Undergraduate Students', 'Postgraduate Students'],
                    datasets: [{
                        label: 'Number of Students',
                        data: [<?php echo $total_undergraduate; ?>, <?php echo $total_postgraduate; ?>],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.5)', // Blue for undergraduate
                            'rgba(255, 99, 132, 0.5)'  // Red for postgraduate
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',   // Solid blue border for undergraduate
                            'rgba(255, 99, 132, 1)'    // Solid red border for postgraduate
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Student Distribution'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = <?php echo $total_undergraduate; ?> + <?php echo $total_postgraduate; ?>;
                                    const value = context.raw;
                                    const percentage = (value / total * 100).toFixed(2);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                },
            });
        });
    </script>
</body>
</html>
