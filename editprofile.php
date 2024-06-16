<?php
// database connection
require_once "database.php";

$errors = array();
$success = "";

// Retrieve user's profile information from the database
$sql = "SELECT * FROM admin";
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

    // Validate the updated profile information
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
        $sql_update = "UPDATE admin SET AdminName=?, AdminEmail=?, AdminHPnum=?, AdminPassword=? WHERE ID=?";
        $stmt_update = mysqli_stmt_init($data);
        $prepareStmt_update = mysqli_stmt_prepare($stmt_update, $sql_update);
        if ($prepareStmt_update) {
            mysqli_stmt_bind_param($stmt_update, "sssss", $name, $email, $phonenum, $pass, $id);
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
            <form action="editprofile.php" method="post" class="edit-profile-form">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $row['AdminName']; ?>">

                <label for="id">Admin ID:</label>
                <input type="text" name="id" id="id" value="<?php echo $row['ID']; ?>">

                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?php echo $row['AdminEmail']; ?>">

                <label for="phonenum">Phone Number:</label>
                <input type="text" name="phonenum" id="phonenum" value="<?php echo $row['AdminHPnum']; ?>">

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter new password">

                <label for="confirm">Confirm Password:</label>
                <input type="password" name="confirm" id="confirm" placeholder="Confirm new password">

                <input type="submit" class="btn" name="update" value="Update">
            </form>
            <a href="viewprofile.php" class="btn view-btn">View</a> <!-- View button -->
        </div>
    </div>
</div>
</body>
</html>

