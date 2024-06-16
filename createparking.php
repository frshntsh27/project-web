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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createparking'])) {
	$ParkingID = $_POST['ParkingID'];
    $vehicleType = $_POST['vehicleType'];
    $Location = $_POST['Location'];
    $Date = $_POST['Date'];
	$availabilityStatus= $_POST['availabilityStatus'];
    $qrImage = $_POST['qrImage'];
    $ClosureReason =$_POST['ClosureReason'];

    // Define parking totals for each location and vehicle type
    $parkingTotals = [
        'Car' => ['Location_A' => 30, 'Location_B' => 30, 'Location_C' => 30],
        'Motorcycle' => ['Location_A' => 20, 'Location_B' => 20, 'Location_C' => 20]
    ];

    // Get the total slots for the specified vehicle type and location
    $totalparking = $parkingTotals[$vehicleType][$Location];

    // Get the current count of used parking slots for the specified vehicle type and location
    $sql_count = "SELECT COUNT(*) as count FROM createparking WHERE vehicleType = ? AND Location = ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("ss", $vehicleType, $Location);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $row_count = $result_count->fetch_assoc();
    $currentCount = $row_count['count'];

    // Check if there are available slots
    if ($currentCount >= $totalparking) {
        echo "Error: No available parking slots for $vehicleType at $Location.";
        exit();
    }

    // Calculate the parking number (current count + 1)
    $parkingNum = $currentCount + 1;

    
	
    // Generate a unique ParkingID (auto-incremented in the database)
    $sql = "INSERT INTO createparking (ParkingID, vehicleType, Location, Date, availabilityStatus, qrImage, totalparking, parkingNum, ClosureReason) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssiss", $ParkingID, $vehicleType, $Location, $Date, $availabilityStatus, $qrImage, $totalparking, $parkingNum, $ClosureReason);
    
    if ($stmt->execute()) {
        // Redirect to viewparking.php after successful insertion
        header("Location: viewparking.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

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
            min-height: 100vh; /* Adjusted min-height */
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
            background: rgba(255, 
			255, 255, 255, 0.3);
        }
        .content {
            width: 100%;
            max-width: 800px;
            padding: 20px;
            text-align: center;
            margin: 0 auto;
        }
       .container {
            background: black; /* Change background color to black */
            padding: 10px; /* Decrease padding to make elements closer together */
            border-radius: 10px;
            text-align: left;
            margin-top: 20px;
            margin-bottom: 20px;
            margin: 0 auto;
            max-width: 500px; /* Limit the width of the container */
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-group:last-child {
            margin-right: 0;
        }
        .form-group label {
            flex: 0 0 150px;
            margin-right: 20px;
            color: white;
        }
        .form-group input,
        .form-group select {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
        }
        .form-group button {
            width: auto;
            padding: 12px;
            background-color: #00b4ab;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-group button:hover {
            background-color: #008f8b;
        }
        .qr-code img {
            margin-top: 20px;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Create New Parking Space - FKPark</title>
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
                <h1>Create New Parking Space</h1><br>
                <form action="createparking.php" method="post">
                    <div class="container">
                        <div class="form-group">
                            <label for="ParkingID">Parking ID</label><br>
                            <input type="number" id="ParkingID" name="ParkingID" required><br>
                        </div>
                        <div class="form-group">
                            <label for="vehicleType">Vehicle Type</label><br>
                            <select id="vehicleType" name="vehicleType" required>
                                <option value="Car">Car</option>
                                <option value="Motorcycle">Motorcycle</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Location">Location</label><br>
                            <select id="Location" name="Location" required>
                                <option value="Location_A">A</option>
                                <option value="Location_B">B</option>
                                <option value="Location_C">C</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Date">Date</label><br>
                            <input type="datetime-local" id="Date" name="Date" required>
                        </div>
						<div class="form-group">
                            <label for="availabilityStatus">Availability Status </label><br>
                            <select id="availabilityStatus" name="availabilityStatus" required>
                                <option value="Available"> Available</option>
                                <option value="Unavailable">Unavailable</option>
								 </select>
								</div>
                        <div class="form-group">
                            <label for="ClosureReason">Closure Reason</label><br>
                            <select id="ClosureReason" name="ClosureReason">
                                <option value="None">None</option>
                                <option value="Faculty Events">Faculty Events</option>
                                <option value="Mowing the Lawns">Mowing the Lawns</option>
                                <option value="Cleaning Building">Cleaning Building</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <input type="hidden" id="qrImage" name="qrImage">
                        <div class="form-group">
                            <button type="button" id="generateQrBtn">Generate QR Code</button>
                        </div>
                        <div class="qr-code" id="qrCodeContainer"></div>
                        <div class="form-group">
                            <button type="button" id="viewQRBtn" style="display: none;">View QR Code</button>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="createparking">Create New Parking Space</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
    <script>
        document.getElementById('generateQrBtn').addEventListener('click', function() {
            var ParkingID = document.getElementById('ParkingID').value;
            var vehicleType = document.getElementById('vehicleType').value;
            var Location = document.getElementById('Location').value;
			var Date = document.getElementById('Date').value;
			var availabilityStatus = document.getElementById('availabilityStatus').value;
			var ClosureReason = document.getElementById('ClosureReason').value;
			

            var parkingInfo = "Parking ID: " + ParkingID + "\n" +
            "Vehicle Type: " + vehicleType + "\n" +
            "Location: " + Location + "\n" +
            "Date: " + Date + "\n" +
            "Availability Status: " + availabilityStatus + "\n" +
			"Closure Reason: " + ClosureReason;

             var qrCodeContainer = document.getElementById('qrCodeContainer');
        var qr = qrcode(0, 'M');
        qr.addData(parkingInfo);
        qr.make();
        var imgTag = qr.createImgTag();
        qrCodeContainer.innerHTML = imgTag;
        qrCodeContainer.setAttribute("data-parking-info", parkingInfo);

        var qrImage = qr.createDataURL();
        document.getElementById('qrImage').value = qrImage;

        var viewQRBtn = document.getElementById('viewQRBtn');
        viewQRBtn.style.display = "block";
    });

    document.getElementById('viewQRBtn').addEventListener('click', function() {
        var parkingInfo = document.getElementById('qrCodeContainer').getAttribute("data-parking-info");
        alert("Parking Information:\n\n" + parkingInfo);
    });
    </script>
</body>
</html>
