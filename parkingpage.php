<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manageparkingarea";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define parking totals for each location and vehicle type
$parkingTotals = [
    'Car' => ['A' => 30, 'B' => 30, 'C' => 30],
    'Motorcycle' => ['A' => 20, 'B' => 20, 'C' => 20]
];

// Calculate available parking spaces
$availableParking = [];

foreach ($parkingTotals as $vehicleType => $locations) {
    foreach ($locations as $location => $total) {
        $sql = "SELECT COUNT(*) AS occupied FROM createparking 
                WHERE Location = 'Location_$location' AND vehicleType = '$vehicleType' AND availabilityStatus = 'Available'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $occupied = $row['occupied'];
        $available = $total - $occupied;
        $availableParking["$vehicleType@$location"] = $available;
    }
}

// Fetch total bookings for each parking area
$totalBookings = [];
$locations = ['A', 'B', 'C'];
foreach ($locations as $location) {
    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE parkArea = '$location'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalBookings[$location] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        body {
            background-image: linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)), url('img/fkom.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        .nav-container {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100px;
            line-height: 100px;
            background: rgba(0, 0, 0, 0.5);
            padding: 0 20px;
            transition: background 0.3s ease;
        }
        .nav-menu {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }
        .nav-menu .menu-item {
            position: relative;
        }
        .nav-menu .menu-item a {
            color: white;
            font-size: 18px;
            text-decoration: none;
            display: block;
            padding: 3px 10px;
            line-height: 30px;
            transition: background 0.3s ease;
        }
        .nav-menu .menu-item a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .nav-menu .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .nav-menu .menu-item:hover .dropdown {
            display: block;
        }
        .nav-menu .dropdown a {
            white-space: nowrap;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            margin-right: auto;
        }
        .nav-logo img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .nav-logo a {
            color: white;
            font-size: 25px;
            font-weight: 600;
            text-decoration: none;
        }
        .nav-button .btn {
            width: 130px;
            height: 40px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: .3s ease;
        }
        #logoutBtn {
            margin-left: 15px;
            color: black;
        }
        .btn.white-btn {
            background: white;
        }
        .btn.white-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .content {
            width: 100%;
            max-width: 800px;
            padding: 20px;
            text-align: center;
            margin: 0 auto;
        }
       .container {
            background: white;
            padding: 10px;
            border-radius: 10px;
            text-align: left;
            margin-top: 20px;
            margin-bottom: 20px;
            margin: 0 auto;
            max-width: 500px;
        }
        .content h1 {
            font-size: 30px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 120%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #333333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #555555;
        }
        tr:hover {
            background-color: #777777;
        }
        a {
            color: #00b4ab;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
       .filter-container {
            margin-bottom: 20px;
        }
        .filter-container select {
            padding: 10px;
            font-size: 16px;
        }
        .filter-wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
        <div class="nav-container">
            <nav class="nav">
                <div class="nav-menu">
                    <div class="menu-item">
                        <a href="#"><i class="fas fa-bars"></i></a>
                        <div class="dropdown">
                            <a href="createparking.php">Create New Parking Space</a>
                            <a href="viewparking.php">View Parking Area</a>
                            <a href="ListParking.php">Parking List</a>
                            <a href="parkingpage.php">Administrator Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="nav-logo">
                    <img src="img/ump.png" alt="UMP Logo">
                    <a href="base.php">FKPark</a>
                </div>
                <div class="nav-button">
                    <button class="btn white-btn" id="logoutBtn">Log Out</button>
                </div>
            </nav>

    <div class="content">
        <h1>Admin Dashboard</h1>
        <div class="container">
            <canvas id="parkingChart" width="400" height="200"></canvas>
        </div><br>
        <div class="container">
            <canvas id="bookingChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for the parking chart from PHP
    const parkingData = {
        labels: ['A', 'B', 'C'],
        datasets: [
            {
                label: 'Cars',
                data: [
                    <?= $availableParking['Car@A'] ?>,
                    <?= $availableParking['Car@B'] ?>,
                    <?= $availableParking['Car@C'] ?>
                ],
                backgroundColor: 'skyblue',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Motorcycles',
                data: [
                    <?= $availableParking['Motorcycle@A'] ?>,
                    <?= $availableParking['Motorcycle@B'] ?>,
                    <?= $availableParking['Motorcycle@C'] ?>
                ],
                backgroundColor: 'lightgreen',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }
        ]
    };

    // Create the parking chart
    const parkingCtx = document.getElementById('parkingChart').getContext('2d');
    const parkingChart = new Chart(parkingCtx, {
        type: 'bar',
        data: parkingData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Available Parking Spaces'
                }
            }
        }
    });

   
    // Prepare data for the booking chart from PHP
    const bookingData = {
        labels: ['A', 'B', 'C'],
        datasets: [
            {
                label: 'Total Bookings',
                data: [
                    <?= $totalBookings['A'] ?>,
                    <?= $totalBookings['B'] ?>,
                    <?= $totalBookings['C'] ?>
                ],
                backgroundColor: 'skyblue', // Adjusted to full opacity
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
    };

    // Create the booking chart
    const bookingCtx = document.getElementById('bookingChart').getContext('2d');
    const bookingChart = new Chart(bookingCtx, {
        type: 'bar',
        data: bookingData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 2 // Specify the step size for the y-axis
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Total Bookings per Location'
                }
            }
        }
    });


</script>
</body>
</html>

