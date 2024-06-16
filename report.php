<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Summon Chart</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.1), url('img/fkom.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .containertop {
            margin-left: 120px;
            margin-top: 200px;
            display: flex;
            align-items: center;
            padding: 20px; /* Add padding around the content */
            background-color: white; /* Add white background */
            border-radius: 10px; /* Add border radius for rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container4 {
            margin-left: 120px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            padding: 15px; /* Reduced padding */
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .containertop h2 {
            margin: 0;
        }

        .containertop button {
            padding: 8px 16px;
            background-color: #bfc3c9;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 200px;
        }

        .containertop button:hover {
            background-color: #0056b3;
        }
        canvas {
            margin-right: 20px;
        }

        .info {
            display: flex;
            flex-direction: column;
            color: black;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .legend-square {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button id="toggleBtn" class="toggle-btn">&#9776;</button>
            <div class="logo">
                <img src="img/ump.png" alt="Logo" width="40">
            </div>
            <div class="title">
                <h1>FKPark</h1>
            </div>
            <span></span>
            <button class="logout-btn">Logout</button>
        </div>
        <div id="sidebar" class="sidebar">
            <ul>
                <li><a href="#Registration">Registration</a></li>
                <li><a href="module4.php">Summon</a></li>
                <li><a href="report.php">Dashboard</a></li>
            </ul>
        </div>

        <?php
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "fkpark";
        $violationNames = []; // Array to store violation names
        $violationData = []; // Array to store violation data

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch all distinct violation names
        $sql = "SELECT DISTINCT name FROM violations";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store each violation name in the array
                $violationNames[] = $row['name'];
            }
        }

        // Fetch data for each violation type
        foreach ($violationNames as $name) {
            $sql = "SELECT COUNT(*) AS count FROM violations WHERE name = '$name'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Store count for each violation type in the array
                $violationData[] = $row['count'];
            } else {
                // If no data found, set count to 0
                $violationData[] = 0;
            }
        }

        $conn->close();
        ?>

    <div class="containertop">
    <h2>Weekly Traffic Summon</h2>
        <a href="edit.php">
        <button type="button">Update</button>
        </a>
    </div>

        <div class="container4">
            <canvas id="pieChart" width="400" height="400"></canvas>
            <div class="info">
                <div class="info-item">Traffic Summon Chart</div>
                <?php foreach ($violationNames as $index => $name) { ?>
                    <div class="info-item">
                        <div class="legend-square" style="background-color: #<?php echo substr(md5($name), 0, 6); ?>; width: 20px; height: 20px; display: inline-block;"></div>
                        <span><?php echo $name; ?></span>
                        <span><?php echo $violationData[$index]; ?>%</span>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('toggleBtn');

                toggleBtn.addEventListener('click', function() {
                    if (sidebar.style.transform === 'translateX(0px)') {
                        sidebar.style.transform = 'translateX(-250px)';
                    } else {
                        sidebar.style.transform = 'translateX(0px)';
                    }
                });

                // Create chart using violation names and data
                var ctx = document.getElementById('pieChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode($violationNames); ?>, // Use violation names as labels
                        datasets: [{
                            data: <?php echo json_encode($violationData); ?>, // Use violation data for chart
                            backgroundColor: [
                                <?php foreach ($violationNames as $name) { ?>
                                    '#<?php echo substr(md5($name), 0, 6); ?>',
                                <?php } ?>
                            ]
                        }]
                    },
                    options: {
                        responsive: false
                    }
                });
            });
        </script>
    </div>
</body>
</html>
