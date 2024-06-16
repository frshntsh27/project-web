<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fkpark";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the QR image ID is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $id = htmlspecialchars($_GET['id']);

    // Fetch the QR image path from the database
    $sql = "SELECT qrImage FROM createparking WHERE ParkingID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the QR image path
        $row = $result->fetch_assoc();
        $qrImagePath = $row['qrImage'];

        // Set the download headers
        header("Content-Disposition: attachment; filename=qr_image.png");
        header("Content-Type: image/png");

        // Output the QR image content
        readfile($qrImagePath);
        exit();
    } else {
        echo "QR image not found.";
        exit();
    }
}

// Fetch parking data from the database
$sql = "SELECT * FROM createparking";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
			min-height: 100%;
        }
        .nav-container {
            position: fixed;
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
            text-align: center; /* Center align the content */
            margin: 0 auto; /* Center the content horizontally */
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(0px);
            padding: 20px;
            border-radius: 10px;
            text-align: left;
            margin-top: 20px; /* Add margin top for spacing */
            margin-bottom: 20px; /* Add margin bottom for spacing */
            margin: 0 auto; /* Center the container horizontally */
			height: 100vh;
			overflow-y: auto;
        }
        .content h1 {
            font-size: 30px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>List of Parking Spaces - FKPark</title>
</head>
<body>
<div class="container">
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
            <h1>List of Parking Spaces</h1>
            <div class="filter-wrapper">
                <div class="filter-container">
                    <label for="vehicleTypeFilter">Filter by Vehicle Type:</label>
                    <select id="vehicleTypeFilter" onchange="filterTable()">
                        <option value="All">All</option>
                        <option value="Car">Car</option>
                        <option value="Motorcycle">Motorcycle</option>
                    </select>
                </div>
                <div class="filter-container">
                    <label for="locationFilter">Filter by Location:</label>
                    <select id="locationFilter" onchange="filterTable()">
                        <option value="All">All</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
            </div>
            <table border="1" id="parkingTable">
                <thead>
                    <tr>
                        <th>Parking ID</th>
                        <th>Vehicle Type</th>
                        <th>Location</th>
						<th>Parking Number</th>
						<th>Date & Time</th>
                        <th>Availability Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
    echo "<tr class='parking-row' data-vehicle-type='". htmlspecialchars($row["vehicleType"]). "' data-location='". htmlspecialchars($row["Location"]). "'>";
    echo "<td>". htmlspecialchars($row["ParkingID"]). "</td>";
    echo "<td>". htmlspecialchars($row["vehicleType"]). "</td>";
    echo "<td>". htmlspecialchars($row["Location"]). "</td>";
	echo "<td>" . htmlspecialchars($row["parkingNum"]) . "</td>";
    echo "<td>". htmlspecialchars($row["Date"]). "</td>";
    echo "<td>";
    if (strtolower($row["availabilityStatus"]) == "available") {
        if (strtolower($row["vehicleType"]) == "car") {
            echo "<i class='fa fa-car' style='color: green;'></i> Available";
        } else {
            echo "<i class='fa fa-motorcycle' style='color: green;'></i> Available";
        }
        echo "<td><a href='booking.php?ParkingID=". htmlspecialchars($row["ParkingID"]). "'>Booking</a> </td>";
    } else {
        if (strtolower($row["vehicleType"]) == "car") {
            echo "<i class='fa fa-car' style='color: red;'></i> Not Available";
        } else {
            echo "<i class='fa fa-motorcycle' style='color: red;'></i> Not Available";
        }
        echo "<td>Not Available</td>";
    }
    echo "</tr>";
}
                    } else {
                        echo "<tr><td colspan='8'>No parking spaces found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <script>
        function filterTable() {
            var vehicleFilter = document.getElementById("vehicleTypeFilter").value;
            var locationFilter = document.getElementById("locationFilter").value;
            var rows = document.querySelectorAll(".parking-row");

            rows.forEach(row => {
                var vehicleType = row.getAttribute("data-vehicle-type").toLowerCase();
                var location = row.getAttribute("data-location").toLowerCase();
                var vehicleMatch = vehicleFilter === "All" || vehicleType === vehicleFilter.toLowerCase();
                var locationMatch = locationFilter === "All" || location === locationFilter.toLowerCase();

                if (vehicleMatch && locationMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</div>
</body>
</html>





