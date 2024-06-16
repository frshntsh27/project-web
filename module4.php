<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Manage Traffic Summon</title>
    <style>
        body {
            background-image: linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)), url(img/fkom.jpg);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            <button id="toggleBtn" class="toggle-btn" style="margin-bottom: 20px;">&#9776;</button>
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
        <div class="main-content">
            <form action="db.php" method="post">
            <h1 style="margin-left: 70px;margin-bottom: 70px; color:black;">Fkomp Violation Form</h1>
                <table>
                    <tr>
                        <th>Date</th>
                        <td>
                            <input type="date" id="dateSelect" name="dateSelect">
                        </td>
                        <th>Time</th>
                        <td>
                            <input type="time" id="timeSelect" name="timeSelect">
                        </td>
                    </tr>
                    <tr>
                        <th>Violation Type</th>
                        <td colspan="2">
                            <select id="userNameInput" onchange="updateOutput()" required name="userNameInput">
                                <option value="" disabled selected>Violation Type</option>
                                <option value="ParkingViolation">Parking Violation</option>
                                <option value="ViolateRegulation">Violate Campus Traffic Regulation</option>
                                <option value="AccidentCaused">Accident Caused</option>
                            </select>
                        </td>
                        <td id="outputColumn1">
                            <!-- Display demerit value here -->
                            <span id="demeritStatic"></span>
                            <!-- Hidden input field for demerit -->
                            <input type="hidden" id="demeritInput" name="demeritInput">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1">Comment :</th>
                        <td colspan="3">
                            <input type="text" id="commentInput" required name="commentInput">
                        </td>
                    </tr>
                    <tr>
                        <th>Vehicle Identification (ID)</th>
                        <td colspan="2">
                            <input type="text" id="vehicleIDInput" required name="vehicleIDInput">
                        </td>
                        <td>
                            <button type="button" onclick="searchFunction()">Search</button>
                        </td>
                    </tr>

                    <tr>
                        <th>Student Identification (ID)</th>
                        
                        <td id="outputColumn2" colspan="3" name="studentIDInput"></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td colspan="3">
                            <input type="text" id="locationInput" required name="locationInput">
                        </td>
                    </tr>
                </table>
                <button type="submit" style="position: absolute; bottom: 5px; right: 90px;">Proceed</button>
            </form>
        </div>
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

        function updateOutput() {
            // Get selected value from the dropdown
            const userName = document.getElementById('userNameInput').value;

            // Define a mapping of violation types to demerit values
            const demeritMapping = {
                'ParkingViolation': 10,
                'ViolateRegulation': 15,
                'AccidentCaused': 20,
            };

            // Get the demerit based on the selected violation type
            const demeritValue = demeritMapping[userName];

            // Display the demerit value
            document.getElementById('demeritStatic').innerText = demeritValue !== undefined ? demeritValue : '';
            // Update the hidden input field for demerit
            document.getElementById('demeritInput').value = demeritValue !== undefined ? demeritValue : '';
        }

        function searchFunction() {

            var vehicleID = document.getElementById("vehicleIDInput").value;
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "search.php?vehicleID=" + vehicleID, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
    var studentID = xhr.responseText;
    // Set the value of the student ID input field
    document.getElementById("outputColumn2").innerHTML = studentID;
    document.getElementById("studentIDInput").value = studentID; // Update the hidden input field
}

            };
            xhr.send();
        }
    </script>
</body>
</html>
