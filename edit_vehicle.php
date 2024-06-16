<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$db = "module1";

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error");
}

// Check if form is submitted for update
if (isset($_POST['update'])) {
    $vehType = isset($_POST["type"]) ? $_POST["type"] : '';
    $vehType = mysqli_real_escape_string($data, $vehType);
    $plateNum = isset($_POST["platenum"]) ? $_POST["platenum"] : '';
    $plateNum = mysqli_real_escape_string($data, $plateNum);
    $originalPlateNum = isset($_POST["original_platenum"]) ? $_POST["original_platenum"] : '';
    $targetDir = "uploads/";
    $grantFile = isset($_FILES["grant"]["name"]) ? $_FILES["grant"]["name"] : '';
    $targetFile = $targetDir . basename($grantFile);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $updateSQL = "UPDATE vehicle SET VehicleType='$vehType', Platenum='$plateNum'";

    // Check if a new grant file is uploaded
    if (!empty($grantFile)) {
        // Validate the uploaded file
        if ($fileType != "pdf" || $_FILES["grant"]["size"] > 2000000) {
            $message = "Error: Only PDF files less than 2MB are allowed to upload";
            $messageType = "danger";
        } else {
            // Move the uploaded file to the uploads directory
            if (move_uploaded_file($_FILES["grant"]["tmp_name"], $targetFile)) {
                $updateSQL .= ", `Grant`='$targetFile'";
            } else {
                $message = "Error uploading file.";
                $messageType = "danger";
            }
        }
    }

    $updateSQL .= " WHERE Platenum='$originalPlateNum'";

    if (!isset($message)) {
        if ($data->query($updateSQL) === TRUE) {
            $_SESSION['message'] = "Vehicle updated successfully.";
            header("Location: view_vehicles.php");
            exit();
        } else {
            $message = "Error: " . $updateSQL . "<br>" . $data->error;
            $messageType = "danger";
        }
    }
}

// Retrieve vehicle information for editing
if (isset($_POST['platenum'])) {
    $platenum = $_POST['platenum'];
    $query = "SELECT * FROM vehicle WHERE Platenum = '$platenum'";
    $result = mysqli_query($data, $query);
    $vehicle = mysqli_fetch_assoc($result);
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
    <link rel="stylesheet" href="style.css">
    <style>
        .edit-btn {
            background-color: #ffc107;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

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
    <title>Edit Vehicle</title>
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
        <div class="container">
            <div class="box form-box">
                <header>Edit Vehicle</header>
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($vehicle)): ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <select name="type" required>
                            <option value="">Select Vehicle Type</option>
                            <option value="car" <?php if($vehicle['VehicleType'] == 'car') echo 'selected'; ?>>Car</option>
                            <option value="motor" <?php if($vehicle['VehicleType'] == 'motor') echo 'selected'; ?>>Motorcycle</option>
                        </select>
                        <div class="field input">
                            <input type="text" name="platenum" id="platenum" placeholder="Plate Number" value="<?php echo $vehicle['Platenum']; ?>" required>
                        </div>
                        <div class="files">
                            <label for="grant">Grant (PDF only, max 2MB):</label>
                            <input type="file" name="grant" id="grant">
                        </div>
                        <input type="hidden" name="original_platenum" value="<?php echo $vehicle['Platenum']; ?>">
                        <div class="field">
                            <input type="submit" class="btn edit-btn" name="update" value="Update">
                            <a href="view_vehicles.php" class="btn back-btn">View</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p>Vehicle not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
