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

$ParkingID = "";
$vehicleType = "";
$Location = "";
$Date = "";
$availabilityStatus = "";
$qrImage = "";
$ClosureReason = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editparking'])) {
    $ParkingID = $_POST['originalParkingID'];  // Use the hidden field value
    $vehicleType = $_POST['vehicleType'];
    $Location = $_POST['Location'];
    $Date = $_POST['Date'];
    $availabilityStatus = $_POST['availabilityStatus'];
    $qrImage = $_POST['qrImage'];
    $ClosureReason = isset($_POST['ClosureReason']) ? $_POST['ClosureReason'] : null;

   // Update data in database
    $sql = "UPDATE createparking SET vehicleType=?, Location=?, Date=?, availabilityStatus=?, qrImage=?, ClosureReason=? WHERE ParkingID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $vehicleType, $Location, $Date, $availabilityStatus, $qrImage, $ClosureReason, $ParkingID);

    if ($stmt->execute()) {
        // Redirect to viewparking.php after successful update
        header("Location: viewparking.php?ParkingID=" . $ParkingID);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ParkingID'])) {
    $ParkingID = $_GET['ParkingID'];
    
    // Retrieve data from database
    $sql = "SELECT * FROM createparking WHERE ParkingID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ParkingID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ParkingID = $row['ParkingID'];
        $vehicleType = $row['vehicleType'];
        $Location = $row['Location'];
        $Date = $row['Date'];
        $availabilityStatus = $row['availabilityStatus'];
        $qrImage = $row['qrImage'];
        $ClosureReason = $row['ClosureReason'];
    } else {
        echo "No record found with Parking ID: " . $ParkingID;
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
             background:black; /* Change background color to black */
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
    <title>Edit Parking Space - FKPark</title>
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
            <h1>Edit Parking Space</h1><br>
            <form action="editparking.php" method="post">
                <div class="container">
                    <div class="form-group">
                        <label for="ParkingID">Parking ID:</label>
                        <input type="text" id="ParkingID" name="ParkingID" value="<?php echo $ParkingID; ?>" disabled>
                        <input type="hidden" name="originalParkingID" value="<?php echo $ParkingID; ?>">
                    </div>
                    <div class="form-group">
                        <label for="vehicleType">Vehicle Type</label><br>
                        <select id="vehicleType" name="vehicleType" required>
                            <option value="Car" <?php if ($vehicleType == 'Car') echo 'selected'; ?>>Car</option>
                            <option value="Motorcycle" <?php if ($vehicleType == 'Motorcycle') echo 'selected'; ?>>Motorcycle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Location">Location</label><br>
                        <select id="Location" name="Location" required>
                            <option value="Location_A" <?php if ($Location == 'Location_A') echo 'selected'; ?>> A</option>
                            <option value="Location_B" <?php if ($Location == 'Location_B') echo 'selected'; ?>> B</option>
                            <option value="Location_C" <?php if ($Location == 'Location_C') echo 'selected'; ?>> C</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Date">Date</label><br>
                        <input type="datetime-local" id="Date" name="Date" value="<?php echo $Date; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="availabilityStatus">Availability Status</label><br>
                        <select id="availabilityStatus" name="availabilityStatus" required>
                            <option value="available" <?php if ($availabilityStatus == 'available') echo 'selected'; ?>>Available</option>
                            <option value="unavailable" <?php if ($availabilityStatus == 'unavailable') echo 'selected'; ?>>Unavailable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ClosureReason">Closure Reason</label><br>
                        <select id="ClosureReason" name="ClosureReason" required>
						<option value="None"<?php if ($ClosureReason == 'None') echo 'selected'; ?>>None</option>
                            <option value="Faculty Events"<?php if ($ClosureReason == 'Faculty Events') echo 'selected'; ?>>Faculty Events</option>
                            <option value="Mowing the Lawns"<?php if ($ClosureReason == 'Mowing the Lawns') echo 'selected'; ?>>Mowing the Lawns</option>
                            <option value="Windows Cleaning"<?php if ($ClosureReason == 'Windows Cleaning') echo 'selected'; ?>>Windows Cleaning</option>
                            <option value="Building Maintenance"<?php if ($ClosureReason == 'Building Maintenance') echo 'selected'; ?>>Building Maintenance</option>
                            <option value="others"<?php if ($ClosureReason == 'Others') echo 'selected'; ?>>Others</option>
                        </select>
                    </div>
                    <input type="hidden" id="qrImage" name="qrImage" value="<?php echo $qrImage; ?>">
                    <div class="form-group">
                        <button type="button" id="generateQrBtn">Generate QR Code</button>
                    </div>
                    <div class="qr-code" id="qrCodeContainer"></div>
                    <div class="form-group">
                        <button type="button" id="viewQRBtn" style="display: none;">View QR Code</button>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="editparking">Update Parking Space</button>
                    </div>
                </div>
            </form>
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

