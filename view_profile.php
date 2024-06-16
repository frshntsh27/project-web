<?php
require_once "database.php";

$sql = "SELECT * FROM student";
$result = mysqli_query($data, $sql);
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
            background-color: #6c757d; /* Gray color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }

        .profile-info {
            margin-bottom: 20px;
        }

        .profile-info h4 {
            margin-bottom: 10px;
        }

        .profile-info p {
            margin-bottom: 5px;
        }

        .edit-btn {
            background-color: #ffc107; /* Green color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-btn:hover {
            background-color: #e0a800; /* Darker green on hover */
        }

        .profile-pic {
            max-width: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
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
            <header>My Profile</header>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="profile-info">
                    <?php
                    $profilePic = $row['ProfilePic'];
                    if (!empty($profilePic)) {
                        echo "<img src='$profilePic' alt='Profile Picture' class='profile-pic'>";
                    } else {
                        echo "<img src='placeholder.jpg' alt='Profile Picture' class='profile-pic'>";
                    }
                    ?>
                    <p>Name: <?php echo $row['StudName']; ?></p>
                    <p>Student ID: <?php echo $row['MatricID']; ?></p>
                    <p>Email: <?php echo $row['StudEmail']; ?></p>
                    <p>Phone Number: <?php echo $row['StudHPnum']; ?></p>
                </div>
            <?php endwhile; ?>
            <a href="edit_profile.php" class="btn edit-btn">Edit</a> <!-- Edit button -->
            <a href="student.php" class="btn back-btn">Back</a> <!-- Back button -->
        </div>
    </div>
</div>
</body>
</html>
