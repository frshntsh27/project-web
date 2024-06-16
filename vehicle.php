<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = "localhost";
$user = "root";
$password = "";
$db = "module1";

// Connect to the database
$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error");
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Retrieve and sanitize form inputs
    $vehType = isset($_POST["type"]) ? $_POST["type"] : '';
    $vehType = mysqli_real_escape_string($data, $vehType);
    $plateNum = isset($_POST["platenum"]) ? $_POST["platenum"] : '';
    $plateNum = mysqli_real_escape_string($data, $plateNum);
    $targetDir = "uploads/";
    $grantFile = isset($_FILES["grant"]["name"]) ? $_FILES["grant"]["name"] : '';
    $targetFile = $targetDir . basename($grantFile);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is a PDF and less than 2MB
    if ($fileType != "pdf" || $_FILES["grant"]["size"] > 2000000) {
        $message = "Error: Only PDF files less than 2MB are allowed to upload";
        $messageType = "danger";
    } else {
        // Move uploaded file to uploads folder
        if (move_uploaded_file($_FILES["grant"]["tmp_name"], $targetFile)) {
            // Insert file information into database
            $sql = "INSERT INTO vehicle (VehicleType, Platenum, `Grant`, folder_path) VALUES ('$vehType', '$plateNum', '$targetFile', '$targetDir')";

            if ($data->query($sql) === TRUE) {
                $message = "Your vehicle has been registered successfully.";
                $messageType = "success";
            } else {
                $message = "Error: " . $sql . "<br>" . $data->error;
                $messageType = "danger";
            }
        } else {
            $message = "Error uploading file.";
            $messageType = "danger";
        }
    }
}

// Close database connection
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .back-btn {
            background-color: #007bff; /* Blue color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
    <title>Vehicle Registration</title>
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
                <div class="item"><a href="profile.php">Profile</a></div>
                <div class="item"><a href="#">Registration</a></div>
                <div class="item"><a href="#">Parking</a></div>
                <div class="item"><a href="#">Booking</a></div>
                <div class="item"><a href="#">Summon</a></div>
                <div class="item"><a href="#">Report</a></div>
            </div>
        </div>
        <div class="container">
            <div class="box form-box">
                <header>Vehicle Registration</header>
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <select name="type" required>
                        <option value="">Select Vehicle Type</option>
                        <option value="car">Car</option>
                        <option value="motor">Motorcycle</option>
                    </select>
                    <div class="field input">
                        <input type="text" name="platenum" id="platenum" placeholder="Plate Number" required>
                    </div>
                    <div class="files">
                        <label for="grant">Grant (PDF only, max 2MB):</label>
                        <input type="file" name="grant" id="grant">
                    </div>
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Submit">
                        <a href="view_vehicles.php" class="btn back-btn">View</a> <!-- View button -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const sideBar = document.getElementById('side-bar');
            const toggleBtn = document.getElementById('toggleBtn');

            toggleBtn.addEventListener('click', function(){
                if (sideBar.style.transform === 'translateX(0px)'){
                    sideBar.style.transform = 'translateX(-250px)';
                } else{
                    sideBar.style.transform = 'translateX(0px)';
                }
            });
        });
    </script>
</body>
</html>
