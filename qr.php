<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body, html {
        height: 100%;
        background-image: linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)), url(img/fkom.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container3 {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;

        }
        .container3 img {
            width: 200px; /* Fixed size for QR code */
            height: 200px; /* Fixed size for QR code *//* Padding same as the QR size */
            background-color: white; /* Background color to visualize the padding */
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #d2d2d2;
    color: rgb(0, 0, 0);
            color: black;
            border: none;
            cursor: pointer;
        }
</style>
<body>
    <div class="main-container">
    <div class="header">
            <button id="toggleBtn" class="toggle-btn">&#9776;</button>
            <div class="logo">
                <img src="img/ump.png" alt="Logo" width="40">
            </div>
            <div class="title">
                <h1>FKPark</h1>
            </div>
            <span></span>
            <button class="logout-btn">Logout</button>
        </div>
        <div id="sidebar" class="sidebar">
            <ul>
                <li><a href="#Registration">Registration</a></li>
                <li><a href="module4.php">Summon</a></li>
                <li><a href="report.php">Dashboard</a></li>
            </ul>
        </div>
        <div class="container3">
            <img src="" alt="QR Code" id="qrImage">
        </div> 
        <button onclick="openGoogleDocs()">Notify User</button>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');

            toggleBtn.addEventListener('click', function() {
                if (sidebar.style.transform === 'translateX(0px)') {
                    sidebar.style.transform = 'translateX(-250px)';
                } else {
                    sidebar.style.transform = 'translateX(0px)';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            generateQR();
        });

        function generateQR() {
            const urlParams = new URLSearchParams(window.location.search);
            const baseURL = 'https://docs.google.com/spreadsheets/d/1fmruR7ta1N42qizRArFHsHR9JWU_3it8VhRZMvo7KME/edit?usp=sharing'; // Replace with your Google Docs URL
            const qrURL = `${baseURL}?${urlParams.toString()}`;

            document.getElementById('qrImage').src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrURL)}`;
        }

        function openGoogleDocs() {
            const urlParams = new URLSearchParams(window.location.search);
            const baseURL = 'https://mail.google.com/mail/u/0/#inbox'; // Replace with your Google Docs URL
            const googleDocsURL = `${baseURL}?${urlParams.toString()}`;
            window.open(googleDocsURL, '_blank');
        }
    </script>
</body>
</html>
