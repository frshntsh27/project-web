<?php
    session_start();
    $connection=mysqli_connect("localhost", "root", "", "module1");

    if(isset($_POST['delete'])) {
        // Handle delete action here
        $id = $_POST['id'];
        $sql = "DELETE FROM student WHERE StudentID='$id'";
        if (mysqli_query($connection, $sql)) {
            $_SESSION['message'] = "Record deleted successfully";
            header("Location: manage.php");
            exit();
        } else {
            $_SESSION['message'] = "Error deleting record: " . mysqli_error($connection);
            header("Location: manage.php");
            exit();
        }
    }
?>
