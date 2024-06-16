<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Handle INSERT operation
    if (isset($_POST['dateSelect'])) {
        $date = $_POST['dateSelect'];
        $time = $_POST['timeSelect'];
        $violationType = isset($_POST['userNameInput']) ? $_POST['userNameInput'] : '';
        $demerit = isset($_POST["demeritInput"]) ? $_POST["demeritInput"] : '';
        $comment = isset($_POST['commentInput']) ? $_POST['commentInput'] : '';
        $vehicleID = isset($_POST['vehicleIDInput']) ? $_POST['vehicleIDInput'] : '';
        $location = isset($_POST['locationInput']) ? $_POST['locationInput'] : '';

        // Retrieve studentID based on vehicleID
        $studentID = "";
        $stmt = $conn->prepare("SELECT Studentid FROM studentdata WHERE Vehicleid = ?");
        $stmt->bind_param("s", $vehicleID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->fetch();
        $stmt->close();

        // Prepare and bind statement for INSERT
        $stmt = $conn->prepare("INSERT INTO violations (date, time, name, demerit, comment, vehicle_id, student_id, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $date, $time, $violationType, $demerit, $comment, $vehicleID, $studentID, $location);

        if ($stmt->execute()) {
            header("Location: output.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Handle DELETE operation
    if (isset($_POST['delete'])) {
        $vehicle_id = $_POST['vehicle_id'];
        $student_id = $_POST['student_id'];

        $sql = "DELETE FROM violations WHERE vehicle_id=? AND student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $vehicle_id, $student_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "error" => $conn->error]);
        }

        $stmt->close();
    }

    // Handle UPDATE operation
    if (isset($_POST['update'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $name = $_POST['name'];
        $demerit = $_POST['demerit'];
        $comment = $_POST['comment'];
        $vehicle_id = $_POST['vehicle_id'];
        $student_id = $_POST['student_id'];
        $location = $_POST['location'];

        $sql = "UPDATE violations SET date=?, time=?, name=?, demerit=?, comment=?, location=? WHERE vehicle_id=? AND student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $date, $time, $name, $demerit, $comment, $location, $vehicle_id, $student_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "error" => $conn->error]);
        }

        $stmt->close();
    }

    $conn->close();
}
?>
