<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <style>
        .view-btn {
            background-color: #007bff; /* Blue color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
        <title>Student Registration</title>
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
                    <div class="item"><a href="#">Profile</a></div>
                    <div class="item"><a href="#">Registration</a></div>
                    <div class="item"><a href="#">Parking</a></div>
                    <div class="item"><a href="#">Booking</a></div>
                    <div class="item"><a href="#">Summon</a></div>
                    <div class="item"><a href="#">Report</a></div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        const side-bar = document.getElementById('side-bar');
                        const toggleBtn = document.getElementById('toggleBtn');

                        toggleBtn.addEventListener('click', function(){
                            if (side-bar.style.transform === 'translateX(0px)'){
                                side-bar.style.transform = 'translateX(-250px)';
                            } else{
                                side-bar.style.transform = 'translateX(0px)';
                            }
                        });
                    });
                </script>
            </div>
            <div class="container">
                <div class="box form-box">
                    <header>Student Registration</header>
                    <?php
                        if (isset($_POST["register"])) {
                            $name = $_POST["name"];
                            $id = $_POST["id"];
                            $level = $_POST["level"]; // Retrieve the selected level from the form
                            $email = $_POST["email"];
                            $phonenum = $_POST["phonenum"];

                            $errors = array();

                            if (empty($name) || empty($id) || $level == "select" || empty($email) || empty($phonenum)) {
                                array_push($errors, "All fields are required!");
                            }
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                array_push($errors, "Email is not valid!");
                            }

                            require_once "database.php";

                            $sql = "SELECT * FROM student WHERE MatricID='$id'";
                            $result = mysqli_query($data, $sql);
                            $rowCount = mysqli_num_rows($result);
                            if ($rowCount > 0) {
                                array_push($errors, "Student already exists!");
                            }

                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo "<div class='alert alert-danger'>$error</div>";
                                }
                            } else {
                                $sql = "INSERT INTO register (StudName, MatricID, StudLevel, StudEmail, StudHPnum) VALUES (?, ?, ?, ?, ?)";
                                $stmt = mysqli_stmt_init($data);
                                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                                if ($prepareStmt) {
                                    mysqli_stmt_bind_param($stmt, "sssss", $name, $id, $level, $email, $phonenum);
                                    mysqli_stmt_execute($stmt);
                                    echo "<div class='alert alert-success'>Student has been registered successfully.</div>";
                                } else {
                                    die("Error");
                                }
                            }
                        }
                    ?>
                    <form action="reg_stud.php" method="post">
                        <div class="field input">
                            <input type="text" name="name" id="name" placeholder="Name">
                        </div>
                        <div class="field input">
                            <input type="text" name="id" id="id" placeholder="Student ID">
                        </div>
                        <div class="field input">
                            <select name="level" id="level">
                                <option value="select">Select Level</option>
                                <option value="undergraduate">Undergraduate</option>
                                <option value="postgraduate">Postgraduate</option>
                            </select>
                        </div>
                        <div class="field input">
                            <input type="text" name="email" id="email" placeholder="Email">
                        </div>
                        <div class="field input">
                            <input type="text" name="phonenum" id="phonenum" placeholder="Phone Number">
                        </div>
                        <div class="field">
                            <input type="submit" class="btn" name="register" value="Register">
                            <a href="manage.php" class="btn view-btn">View</a> <!-- View button -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>