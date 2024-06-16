<?php
include "db2.php";
$id = $_GET["vehicleID"];
$sql = "DELETE FROM `violations` WHERE id = $vehicleID";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: index.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}