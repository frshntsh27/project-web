<!DOCTYPE html>
<html lang="en">
<head>
    <style>
       * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        body {
            background-image: linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)), url('img/fkom.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
        }
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Adjusted min-height */
            flex-direction: column;
        }
          .nav {
		position: fixed;
		top: 0;
		display: flex;
		justify-content: space-between;
		align-items: center;
		width: 100%;
		height: 100px;
		line-height: 100px;
		z-index: 1000;
		background: black; /* Change background color to black */
		padding: 0 20px;
		transition: background 0.3s ease; /* Add transition for smooth color change */
		}

        .nav-menu {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }
        .nav-menu .menu-item {
            position: relative;
        }
        .nav-menu .menu-item a {
            color: white;
            font-size: 18px;
            text-decoration: none;
            display: block;
            padding: 3px 10px;
            line-height: 30px;
            transition: background 0.3s ease;
        }
        .nav-menu .menu-item a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .nav-menu .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .nav-menu .menu-item:hover .dropdown {
            display: block;
        }
        .nav-menu .dropdown a {
            white-space: nowrap;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            margin-right: auto;
        }
        .nav-logo img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .nav-logo a {
            color: white;
            font-size: 25px;
            font-weight: 600;
            text-decoration: none;
        }
        .nav-button .btn {
            width: 130px;
            height: 40px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: .3s ease;
        }
        #logoutBtn {
            margin-left: 15px;
            color: black;
        }
        .btn.white-btn {
            background: white;
        }
        .btn.white-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
       .content {
            width: 100%; /* Adjusted width */
            max-width: 800px;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 20px;
            border-radius: 10px;
            text-align: left;
        }
        .content h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .content ul {
            list-style-type: none;
        }
        .content ul li {
            margin: 10px 0;
        }
        .content ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .content ul li a:hover {
            text-decoration: underline;
        }
		

    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="base.css">
    <title>FKPark</title>
</head>
<body>
   <div class="wrapper">
        <nav class="nav">
            <div class="nav-menu">
                <div class="menu-item">
                    <a href="#"><i class="fas fa-bars"></i></a>
                    <div class="dropdown">
                        <a href="">Profile</a>
                        <a href="">Registraion</a>
                        <a href="parkingpage.php">Parking</a>
                        <a href="booking.php">Booking</a>
                        <a href="index.html">Summon</a>
                        <a href="index.html">Report</a>
                    </div>
                </div>
            </div>
            <div class="nav-logo">
                <img src="img/ump.png" alt="UMP Logo">
                <a href="base.php">FKPark</a>
            </div>
            <div class="nav-button">
                <button class="btn white-btn" id="logoutBtn">Log Out</button>
            </div>
        </nav>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const sidebar = document.querySelector('.side-bar');
            const toggleBtn = document.getElementById('toggleBtn');

            toggleBtn.addEventListener('click', function(){
                if (sidebar.style.transform === 'translateX(0px)'){
                    sidebar.style.transform = 'translateX(-250px)';
                } else{
                    sidebar.style.transform = 'translateX(0px)';
                }
            });
        });
    </script>
</body>
</html>

