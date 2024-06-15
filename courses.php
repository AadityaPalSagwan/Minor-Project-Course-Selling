<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enroll'])) {
    // Include database connection file
    require_once "config.php";
    
    // Get enrollment data
    $courseN = $_POST['course_name'];
    $duration = $_POST['duration'];
    $fees = $_POST['fees'];
    $username = $_SESSION['name'];
    
    // Insert enrollment data into database
    $sql = "INSERT INTO course (username, courseN, duration, fees) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssis", $username, $courseN, $duration, $fees);
        if ($stmt->execute()) {
            // Enrollment successful
            // Redirect back to the same page
            $_SESSION['course_name'] = $courseN;
            header("Location: courses.php");
            exit(); // Ensure that script execution stops after redirection
        } else {
            // Error handling
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Error handling
        echo "Error: " . $conn->error;
    }
    $conn->close();
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
  <link rel="shortcut icon" href="./assets/image/favicon.ico" type="image/x-icon">
  <!-- Link to your external CSS file -->
  <link rel="stylesheet" href="./assets/CSS/style1.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="./assets/image/logo.png" width="150px" alt="logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.html">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Contact.html">Contact</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto" style="padding-right: 90px;">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="https://img.icons8.com/metro/26/000000/guest-male.png"><?php echo $_SESSION['name'] ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="profile.php">Your Profile</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
            <!-- Add more dropdown items here if needed -->
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4" style="height:150px">
    <h3 class="main-heading" style="text-align: center;"><?php echo "Hi , " . $_SESSION['name'] ?>👋</h3>
    <hr>
    <h4 class="main-note" style="text-align: center; font-size:19px; color:#333; font-weight:400;">Let’s help you land your dream career</h4>
  </div>

  <div class="courses">
        <div class="course-card">
            <img src="./assets/image/pepsheco-student.png.webp" alt="Course Image">
            <div class="course-details">
            <form method="post">
                <input type="hidden" name="course_name" value="Web Development">
                <input type="hidden" name="duration" value="6">
                <input type="hidden" name="fees" value="1200">
                <h2>Web Development</h2>
                <p>Learn HTML, CSS, JavaScript and more to build modern websites and web applications.</p>
                <div class="course-meta">
                    <span class="duration">6 Weeks</span>
                    <span class="level">Beginner</span>
                    <span class="fees">1200</span>
                </div>
                <button type="submit" name="enroll">Enroll Now</button>
            </form>
            </div>
        </div>
       
    </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>