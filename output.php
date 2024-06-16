<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f8f8;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            z-index: 1000;
        }

        .header .logo img {
            vertical-align: middle;
        }

        .header .title {
            flex-grow: 1;
            text-align: center;
        }

        .header .logout-btn {
            background-color: #ff4b5c;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .sidebar {
            position: fixed;
            top: 50px;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #333;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .content {
            margin-top: 60px;
            margin-left: 260px;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .edit-mode input {
            display: block;
            width: 100%;
        }

        .edit-mode span {
            display: none;
        }

        .edit-mode .edit-button {
            display: none;
        }

        .edit-mode .save-button {
            display: inline-block;
        }

        .view-mode input,
        .view-mode .save-button {
            display: none;
        }

        .view-mode span,
        .view-mode .edit-button {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="header">
    <button id="toggleBtn" class="toggle-btn" style="margin-bottom: 20px;">&#9776;</button>
    <div class="logo">
        <img src="img/ump.png" alt="Logo" width="40">
    </div>
    <div class="title">
        <h1>FKPark</h1>
    </div>
    <button class="logout-btn">Logout</button>
</div>
<div class="container">
    <div id="sidebar" class="sidebar">
        <ul>
            <li><a href="#Registration">Registration</a></li>
            <li><a href="module4.php">Summon</a></li>
            <li><a href="report.php">Dashboard</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Violations Data</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Violation Type</th>
                <th>Demerit</th>
                <th>Comment</th>
                <th>Vehicle ID</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
            <?php
            $servername = "127.0.0.1";
            $username = "root";
            $password = "";
            $dbname = "fkpark";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve data from the database
            $sql = "SELECT * FROM violations";
            $result = $conn->query($sql);

            // Display data in a table
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='view-mode'>
                        <td><span>" . $row["date"] . "</span><input type='text' value='" . $row["date"] . "'></td>
                        <td><span>" . $row["time"] . "</span><input type='text' value='" . $row["time"] . "'></td>
                        <td><span>" . $row["name"] . "</span><input type='text' value='" . $row["name"] . "'></td>
                        <td><span>" . $row["demerit"] . "</span><input type='text' value='" . $row["demerit"] . "'></td>
                        <td><span>" . $row["comment"] . "</span><input type='text' value='" . $row["comment"] . "'></td>
                        <td><span>" . $row["vehicle_id"] . "</span><input type='text' value='" . $row["vehicle_id"] . "' readonly></td>
                        <td><span>" . $row["student_id"] . "</span><input type='text' value='" . $row["student_id"] . "' readonly></td>
                        <td><span>" . $row["location"] . "</span><input type='text' value='" . $row["location"] . "'></td>
                        <td>
                            <button class='edit-button'>Edit</button>
                            <button class='save-button' style='display: none;'>Save</button>
                            <button class='delete-button'>Delete</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>0 results</td></tr>";
            }
            $conn->close();
            ?>
        </table>
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-button').forEach(function(editButton) {
        editButton.addEventListener('click', function() {
            var row = this.closest('tr');
            row.classList.remove('view-mode');
            row.classList.add('edit-mode');

            var saveButton = row.querySelector('.save-button');
            saveButton.style.display = 'inline-block';
            this.style.display = 'none';  // Hide the edit button
        });
    });

    document.querySelectorAll('.save-button').forEach(function(saveButton) {
        saveButton.addEventListener('click', function() {
            var row = this.closest('tr');
            var inputs = row.querySelectorAll('input');
            var spans = row.querySelectorAll('span');
            var values = [];

            inputs.forEach(function(input) {
                values.push(input.value);
            });

            // Send the updated data to the server using AJAX
            var formData = new FormData();
            formData.append('date', values[0]);
            formData.append('time', values[1]);
            formData.append('name', values[2]);
            formData.append('demerit', values[3]);
            formData.append('comment', values[4]);
            formData.append('vehicle_id', values[5]);
            formData.append('student_id', values[6]);
            formData.append('location', values[7]);
            formData.append('update', 'true'); // Add update flag

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'db.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the table with saved data if successful
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        spans.forEach(function(span, index) {
                            span.textContent = values[index];
                        });
                        // Switch to view mode
                        row.classList.remove('edit-mode');
                        row.classList.add('view-mode');

                        // Restore button states
                        var editButton = row.querySelector('.edit-button');
                        saveButton.style.display = 'none';
                        editButton.style.display = 'inline-block';
                    } else {
                        console.error('Error updating data:', response.error);
                    }
                } else {
                    console.error('Error updating data:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Network error occurred while updating data.');
            };
            xhr.send(formData);
        });
    });

    document.querySelectorAll('.delete-button').forEach(function(deleteButton) {
        deleteButton.addEventListener('click', function() {
            var row = this.closest('tr');
            var vehicleId = row.querySelector('input[value="' + row.querySelector('td:nth-child(6) span').textContent + '"]').value;
            var studentId = row.querySelector('input[value="' + row.querySelector('td:nth-child(7) span').textContent + '"]').value;
            var confirmDelete = confirm('Are you sure you want to delete this entry?');
            if (confirmDelete) {
                var formData = new FormData();
                formData.append('vehicle_id', vehicleId);
                formData.append('student_id', studentId);
                formData.append('delete', 'true'); // Add delete flag

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'db.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            // Remove the row from the table if successful
                            row.remove();
                        } else {
                            console.error('Error deleting data:', response.error);
                        }
                    } else {
                        console.error('Error deleting data:', xhr.statusText);
                    }
                };
                xhr.onerror = function() {
                    console.error('Network error occurred while deleting data.');
                };
                xhr.send(formData);
            }
        });
    });
});
</script>

</body>
</html>
