<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="base.css">
    <style>
        .content h1 {
            color: white;
        }
    </style>
    <title>Guard</title>
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
                <div class="item"><a href="veh_app.php">Registration</a></div>
                <div class="item"><a href="#">Summon</a></div>
                <div class="item"><a href="#">Report</a></div>
            </div>
        </div>
        <div class="content">
            <h1>Welcome! This is guard page.</h1>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const sideBar = document.querySelector('.side-bar');
            const toggleBtn = document.getElementById('toggleBtn');

            toggleBtn.addEventListener('click', function(){
                if (sideBar.style.transform === 'translateX(0px)'){
                    sideBar.style.transform = 'translateX(-250px)';
                } else {
                    sideBar.style.transform = 'translateX(0px)';
                }
            });
        });
    </script>
</body>
</html>
