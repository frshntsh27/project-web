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

// Fetch all parking information
$sql = "SELECT * FROM createparking";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $parkingData = "";
    while($row = $result->fetch_assoc()) {
        $parkingData .= "Parking ID: " . $row['ParkingID'] . "\n";
        $parkingData .= "Vehicle Type: " . $row['vehicleType'] . "\n";
        $parkingData .= "Location: " . $row['Location'] . "\n";
		$parkingData .= "Date: " . $row['Date'] . "\n";
        $parkingData .= "Availability Status: " . $row['availabilityStatus'] . "\n\n";
		$parkingData .= "Closure Reason: " . $row['ClosureReason'] . "\n";
		
    }
    
    // Force download the data as a text file
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="parking_information.txt"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    echo $parkingData;
} else {
    echo "No parking information found";
}

$conn->close();
?>
