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

// Check if ParkingID is set in the URL
if (isset($_GET['id'])) {
    $ParkingID = $_GET['id'];

    // Check if confirmation is received
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        // Delete the parking space from the database
        $sql = "DELETE FROM createparking WHERE ParkingID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ParkingID);
        
        if ($stmt->execute()) {
            echo "Parking space deleted successfully";
            // Optionally redirect to viewparking.php after deletion
            header("Location: viewparking.php");
            exit();
        } else {
            echo "Error deleting parking space: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        // Show confirmation prompt
        echo "<script>
                if (confirm('Are you sure you want to delete this parking space?')) {
                    window.location.href = 'deleteparking.php?id=$ParkingID&confirm=yes';
                } else {
                    window.location.href = 'viewparking.php'; // Redirect to viewparking.php if canceled
                }
              </script>";
    }
} else {
    echo "No ParkingID specified.";
}

$conn->close();
?>
