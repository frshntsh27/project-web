<?php
// database connection
require_once "database.php";

$errors = array();
$success = "";

if (isset($_POST["add"])) {
    $name = $_POST["name"];
    $id = $_POST["id"];
    $email = $_POST["email"];
    $phonenum = $_POST["phonenum"];
    $pass = $_POST["password"];
    $confirm = $_POST["confirm"];

    // File upload handling
    $targetDir = "profile_pics/";
    $profilePic = $_FILES["profile_pic"]["name"];
    $targetFile = $targetDir . basename($profilePic);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file
    $validExtensions = array("jpg", "jpeg", "png", "gif");
    if (!in_array($fileType, $validExtensions)) {
        array_push($errors, "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        array_push($errors, "File already exists. Please rename your file.");
    }

    // Check file size (max 5MB)
    if ($_FILES["profile_pic"]["size"] > 5000000) {
        array_push($errors, "File size exceeds the maximum limit of 5MB.");
    }

    // Check if all fields are filled
    if (empty($name) || empty($id) || empty($email) || empty($phonenum) || empty($pass) || empty($confirm)) {
        array_push($errors, "All fields are required!");
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid!");
    }

    // Validate password
    if (strlen($pass) < 8) {
        array_push($errors, "Password must be at least 8 characters long!");
    }

    // Confirm password
    if ($pass !== $confirm) {
        array_push($errors, "Password does not match!");
    }

    // Check if student already exists
    $sql = "SELECT * FROM student WHERE MatricID='$id'";
    $result = mysqli_query($data, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Student already exists!");
    }

    // If no errors, proceed with insertion
    if (count($errors) == 0) {
        // Move uploaded profile picture to target directory
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            // Insert user data into database
            $sql = "INSERT INTO student (StudName, MatricID, StudEmail, StudHPnum, StudPassword, ProfilePic) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($data);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "ssssss", $name, $id, $email, $phonenum, $pass, $targetFile);
                mysqli_stmt_execute($stmt);

                // Add to login table
                $sql_login = "INSERT INTO login (username, password, type) VALUES (?, ?, 'Student')";
                $stmt_login = mysqli_stmt_init($data);
                $prepareStmt_login = mysqli_stmt_prepare($stmt_login, $sql_login);
                if ($prepareStmt_login) {
                    mysqli_stmt_bind_param($stmt_login, "ss", $id, $pass);
                    mysqli_stmt_execute($stmt_login);
                }

                $success = "Your profile has been added.";
            } else {
                die("Error");
            }
        } else {
            array_push($errors, "Error uploading profile picture.");
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
    </style>
    <title>Profile</title>
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
            <header>Profile</header>
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
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <div class="field input">
                    <input type="text" name="name" id="name" placeholder="Name">
                </div>
                <div class="field input">
                    <input type="text" name="id" id="id" placeholder="Student ID">
                </div>
                <div class="field input">
                    <input type="text" name="email" id="email" placeholder="Email">
                </div>
                <div class="field input">
                    <input type="text" name="phonenum" id="phonenum" placeholder="Phone Number">
                </div>
                <div class="field input">
                    <input type="password" name="password" id="password" placeholder="Password">
                </div>
                <div class="field input">
                    <input type="password" name="confirm" id="confirm" placeholder="Confirm Password">
                </div>
                <!-- Profile Picture Input Field -->
                <div class="field input">
                    <label>Profile Picture:</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="add" value="Add">
                    <a href="view_profile.php" class="btn view-btn">View</a> <!-- View button -->
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
