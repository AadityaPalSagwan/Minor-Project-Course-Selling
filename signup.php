<?php
require_once "config.php";

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Check if name is empty
    if (empty(trim($_POST["name"]))) {
        $name_err = "name cannot be blank";
    } else {
        $name = trim($_POST['name']);
    }

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "email cannot be blank";
    } else {
        $sql = "SELECT id FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST['email']);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken";
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                // Log error instead of echoing
                error_log("Error executing statement: " . mysqli_error($conn));
                echo "Something went wrong";
            }
            mysqli_stmt_close($stmt);
        } else {
            // Log error instead of echoing
            error_log("Error preparing statement: " . mysqli_error($conn));
            echo "Something went wrong";
        }
    }

    // Check for password
    if (empty(trim($_POST['password']))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST['password'])) < 5) {
        $password_err = "Password cannot be less than 5 characters";
    } else {
        $password = trim($_POST['password']);
    }

    // Check for confirm password field
    if (empty(trim($_POST['confirm_password'])) || trim($_POST['password']) != trim($_POST['confirm_password'])) {
        $confirm_password_err = "Passwords should match";
    }

    // If there were no errors, go ahead and insert into the database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO user (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_password);
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if (mysqli_stmt_execute($stmt)) {
                // Redirect only if insertion is successful
                header("location: login.php");
                exit(); // Ensure script execution stops after redirection
            } else {
                // Log error instead of echoing
                error_log("Error executing statement: " . mysqli_error($conn));
                echo "Something went wrong... cannot redirect!";
            }
            mysqli_stmt_close($stmt);
        } else {
            // Log error instead of echoing
            error_log("Error preparing statement: " . mysqli_error($conn));
            echo "Something went wrong";
        }
    }

    // Close database connection
    mysqli_close($conn);
}
?>



<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>InternShala</title>
    <link rel="stylesheet" href="./CSS/style.css">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<a class="navbar-brand" href="/"><img src="./assets/image/logo.png"  width="150px" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="signup.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h3>Please Register Here:</h3>
    <hr>
    <form action="" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputName">name</label>
                <input type="text" class="form-control" name="name" id="inputName" placeholder="name" Required>
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group col-md-6">
                <label for="inputEmail4">email</label>
                <input type="text" class="form-control" name="email" id="inputEmail4" placeholder="Email">
                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Password</label>
                <input type="password" class="form-control" name ="password" id="inputPassword4" placeholder="Password">
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword">Confirm Password</label>
            <input type="password" class="form-control" name ="confirm_password" id="inputPassword" placeholder="Confirm Password">
            <span class="text-danger"><?php echo $confirm_password_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>

        <p>Already have an account <a href="login.php" style="color:dodgerblue">Login</a>.</p>
    </form>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i
