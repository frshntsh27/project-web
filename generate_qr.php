<?php
session_start();
require 'phpqrcode/phpqrcode/qrlib.php';

$host = "localhost";
$user = "root";
$password = "";
$db = "module1";

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['platenum'])) {
    // Retrieve vehicle information from the database based on platenum
    $platenum = $_POST['platenum'];
    $query = "SELECT * FROM vehicle WHERE Platenum = '$platenum'";
    $result = mysqli_query($data, $query);
    $vehicle = mysqli_fetch_assoc($result);
    
    // Directory to store generated QR codes
    $tempDir = 'qrcodes/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir);
    }
    
    $fileName = $tempDir . 'qr_' . $platenum . '.png';
    $qrContent = "Vehicle Plate Number: " . $platenum;

    // Generate the QR code
    QRcode::png($qrContent, $fileName, QR_ECLEVEL_L, 10);
}

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
    <style>
        .back-btn, .download-btn {
            background-color: #6c757d; /* Gray color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        .back-btn:hover, .download-btn:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }
    </style>
    <title>QR Code</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Vehicle Information</h4>
                    </div>
                    <div class="card-body">
                        <!-- Display vehicle information here -->
                        <?php if(isset($vehicle)) : ?>
                            <p>Vehicle Type: <?php echo $vehicle['VehicleType']; ?></p>
                            <p>Plate Number: <?php echo $vehicle['Platenum']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4>QR Code</h4>
                    </div>
                    <div class="card-body">
                        <!-- Display QR code here -->
                        <?php if(isset($fileName)) : ?>
                            <img src="<?php echo $fileName; ?>" alt="QR Code">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <!-- Back button -->
                <a href="#" class="btn back-btn" onclick="history.back()">Back</a>
                <!-- Download button -->
                <?php if(isset($fileName)) : ?>
                    <a href="<?php echo $fileName; ?>" class="btn download-btn" download>Download</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
