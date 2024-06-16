<?php
// database connection
require_once "database.php";

$errors = array(); // Initialize $errors as an empty array
$success = "";

// Retrieve user's profile information from the database
$sql = "SELECT * FROM student";
$result = mysqli_query($data, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST["update"])) {
    // Retrieve updated profile information from the form
    $name = $_POST["name"];
    $id = $_POST["id"];
    $email = $_POST["email"];
    $phonenum = $_POST["phonenum"];
    $pass = $_POST["password"];
    $confirm = $_POST["confirm"];

    // File upload handling for profile picture
    $targetDir = "profile_pics/";
    $profilePic = $_FILES["profile_pic"]["name"];
    $targetFile = $targetDir . basename($profilePic);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file
    $validExtensions = array("jpg", "jpeg", "png", "gif");
    if (!empty($profilePic)) {
        if (!in_array($fileType, $validExtensions)) {
            array_push($errors, "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.");
        }
        // Check file size (max 5MB)
        if ($_FILES["profile_pic"]["size"] > 5000000) {
            array_push($errors, "File size exceeds the maximum limit of 5MB.");
        }
    }

    // Validate other profile information
    if (empty($name) OR empty($id) OR empty($email) OR empty($phonenum) OR empty($pass) OR empty($confirm)) {
        array_push($errors, "All fields are required!");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid!");
    }
    if (strlen($pass) < 8) {
        array_push($errors, "Password must be at least 8 characters long!");
    }
    if ($pass !== $confirm) {
        array_push($errors, "Password does not match!");
    }

    // Update the profile information in the database if there are no errors
    if (count($errors) == 0) {
        // Move uploaded profile picture to target directory
        if (!empty($profilePic)) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
                // Update profile picture path in the database
                $profilePicPath = $targetFile;
            } else {
                array_push($errors, "Error uploading profile picture.");
            }
        } else {
            // If no new profile picture uploaded, keep the existing path
            $profilePicPath = $row['ProfilePic'];
        }

        // Update other profile information in the database
        $sql_update = "UPDATE student SET StudName=?, StudEmail=?, StudHPnum=?, StudPassword=?, ProfilePic=? WHERE MatricID=?";
        $stmt_update = mysqli_stmt_init($data);
        $prepareStmt_update = mysqli_stmt_prepare($stmt_update, $sql_update);
        if ($prepareStmt_update) {
            mysqli_stmt_bind_param($stmt_update, "ssssss", $name, $email, $phonenum, $pass, $profilePicPath, $id);
            mysqli_stmt_execute($stmt_update);
            $success = "Your profile has been updated.";
        } else {
            die("Error");
        }
    }
}
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
        .view-btn {
            background-color: #007bff; /* Blue color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .edit-profile-form {
            margin-bottom: 20px;
        }

        .edit-profile-form label {
            display: block;
            margin-bottom: 5px;
        }

        .edit-profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-profile-form .btn {
            background-color: #ffc107; /* Yellow color */
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-profile-form .btn:hover {
            background-color: #e0a800; /* Darker yellow on hover */
        }
        .alert {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da; /* Light red */
            color: #721c24; /* Dark red */
        }

        .alert-success {
            background-color: #d4edda; /* Light green */
            color: #155724; /* Dark green */
        }

        .profile-pic-container {
            text-align: center; /* Center the image */
            margin-bottom: 20px; /* Add some space below the image */
        }

        .profile-pic {
            max-width: 200px; /* Set the maximum width of the image */
            max-height: 200px; /* Set the maximum height of the image */
            width: auto; /* Ensure the image scales proportionally */
            height: auto; /* Ensure the image scales proportionally */
        }
    </style>
    <title>Edit Profile</title>
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
            <div class="item"><a href="#">Profile</a></div>
            <div class="item"><a href="#">Registration</a></div>
            <div class="item"><a href="#">Parking</a></div>
            <div class="item"><a href="#">Booking</a></div>
            <div class="item"><a href="#">Summon</a></div>
            <div class="item"><a href="#">Report</a></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const sideBar = document.querySelector('.side-bar');
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
    </div>
    <div class="container">
        <div class="box form-box">
            <header>Edit Profile</header>
            <?php
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            if ($success) {
                echo "<div class='alert alert-success'>$success</div>";
            }
            ?>
            <form action="edit_profile.php" method="post" class="edit-profile-form" enctype="multipart/form-data">
                <div class="profile-pic-container">
                    <!-- Existing profile picture -->
                    <img src="<?php echo $row['ProfilePic']; ?>" alt="Profile Picture" class="profile-pic">
                </div>

                <!-- Upload new profile picture -->
                <label for="profile_pic">Profile Picture:</label>
                <input type="file" name="profile_pic" id="profile_pic">
                
                <!-- Other profile information fields -->
                <!-- Name, Student ID, Email, Phone Number, Password, Confirm Password -->
                
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $row['StudName']; ?>">

                <label for="id">Student ID:</label>
                <input type="text" name="id" id="id" value="<?php echo $row['MatricID']; ?>">

                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo $row['StudEmail']; ?>">

                <label for="phonenum">Phone Number:</label>
                <input type="text" name="phonenum" id="phonenum" value="<?php echo $row['StudHPnum']; ?>">

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter new password">

                <label for="confirm">Confirm Password:</label>
                <input type="password" name="confirm" id="confirm" placeholder="Confirm new password">

                <input type="submit" class="btn" name="update" value="Update">
            </form>
            <a href="view_profile.php" class="btn view-btn">View</a> <!-- View button -->
        </div>
    </div>
</div>
</body>
</html>
