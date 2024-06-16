<?php
include('db.php');

if (isset($_GET['bookingId'])) {
    $bookingId = $_GET['bookingId'];

    // Fetch booking information
    $query = "SELECT * FROM bookings WHERE id = '$bookingId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
    } else {
        echo "No booking found with the provided ID.";
        exit();
    }
} else {
    echo "No booking ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        body {
            background-image: linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)), url(img/fkom.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 110vh;
        }
        .nav {
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-around;
            width: 100%;
            height: 100px;
            line-height: 100px;
            z-index: 100;
        }
        .nav-logo p {
            color: white;
            font-size: 25px;
            font-weight: 600;
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
        .side-bar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            width: 290px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .side-bar .menu {
            width: 100%;
            margin-top: 80px;
        }
        .side-bar .menu .item {
            position: relative;
            cursor: pointer;
        }
        .side-bar .menu .item a {
            color: white;
            font-size: 16px;
            text-decoration: none;
            display: block;
            padding: 5px 30px;
            line-height: 60px;
        }
        .side-bar .menu .item a:hover {
            background: #00b4ab;
            transition: 0.3s ease;
        }
        .content {
            margin-left: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }
        .content h2 {
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 10px;
        }
        .button-container {
            margin-top: 20px;
        }
        .button-container .btn {
            margin-right: 10px;
            padding: 10px 20px;
            background-color: #00b4ab;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-container .btn:hover {
            background-color: #008f8a;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>FKPark</p>
            </div>
            <div class="nav-button">
                <button class="btn white-btn" id="logoutBtn">Log Out</button>
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
                <div class="item"><a href="admin_dashboard.php">Admin Dashboard</a></div>
            </div>
        </div>
        <div class="content">
            <h2>View Booking</h2>
            <p><strong>Plate Number:</strong> <?php echo htmlspecialchars($booking['carPlate']); ?></p>
            <p><strong>Parking Area:</strong> <?php echo htmlspecialchars($booking['parkArea']); ?></p>
            <p><strong>Start Time:</strong> <?php echo htmlspecialchars($booking['startTime']); ?></p>
            <p><strong>End Time:</strong> <?php echo htmlspecialchars($booking['endTime']); ?></p>
            <div class="button-container">
                <button class="btn" onclick="generateQRCode()">Generate QR Code</button>
                <button class="btn" onclick="cancelBooking()">Cancel Booking</button>
                <button class="btn" onclick="editBooking()">Edit</button>
                <button class="btn" onclick="goToBookingHistory()">View Booking History</button>
            </div>
        </div>
    </div>

    <script>
        function generateQRCode() {
            const bookingId = <?php echo json_encode($bookingId); ?>;
            window.location.href = `generate_qrcode.php?bookingId=${bookingId}`;
        }

        function cancelBooking() {
            const bookingId = <?php echo json_encode($bookingId); ?>;
            if (confirm("Are you sure you want to cancel this booking?")) {
                window.location.href = `cancel_booking.php?bookingId=${bookingId}`;
            }
        }

        function editBooking() {
            const bookingId = <?php echo json_encode($bookingId); ?>;
            window.location.href = `booking.php?bookingId=${bookingId}`;
        }

        function goToBookingHistory() {
            window.location.href = 'booking_history.php';
        }
    </script>
</body>
</html>




