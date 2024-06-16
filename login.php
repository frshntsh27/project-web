<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "module1";

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error");
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    $type = htmlspecialchars($_POST["type"]);

    if ($type == "select") {
        $error_message = "Please select a user type!";
    } else {
        if ($type == "Student") {
            $sql = "SELECT * FROM student WHERE MatricID = ?";
        } else {
            $sql = "SELECT * FROM login WHERE username = ? AND type = ?";
        }
        $stmt = mysqli_prepare($data, $sql);
        if ($type == "Student") {
            mysqli_stmt_bind_param($stmt, "s", $username);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $username, $type);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $storedPassword = ($type == "Student") ? $row["StudPassword"] : $row["password"];
            
            if ($password === $storedPassword) {
                if ($type == "Admin") {
                    header("Location: admin.php");
                } elseif ($type == "Student") {
                    header("Location: student.php");
                } elseif ($type == "Guard") {
                    header("Location: guard.php");
                }
                exit(); // Ensure script halts after redirection
            } else {
                $error_message = "Incorrect password!";
            }
        } else {
            $error_message = "Username or password incorrect!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
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
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <?php
            if (!empty($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
            <form action="login.php" method="post">
                <div class="field input">
                    <input type="text" name="username" id="username" placeholder="ID" required>
                </div>
                <div class="field input">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="field input">
                    <select name="type" required>
                        <option value="select">Select</option>
                        <option value="Admin">Admin</option>
                        <option value="Student">Student</option>
                        <option value="Guard">Guard</option>
                    </select>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
