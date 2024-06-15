<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit; // Ensure no further execution
}

// Include database connection file
require_once "config.php";

// Delete course if delete button is clicked
if(isset($_POST['delete_course'])) {
    $courseToDelete = $_POST['course_to_delete'];
    $sql_delete = "DELETE FROM course WHERE username = ? AND courseN = ?";
    if ($stmt_delete = $conn->prepare($sql_delete)) {
        $stmt_delete->bind_param("ss", $_SESSION['name'], $courseToDelete);
        $stmt_delete->execute();
        $stmt_delete->close();
        // Redirect to refresh the page after deletion
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle completing a course
if(isset($_POST['complete_course'])) {
    $courseToComplete = $_POST['course_to_complete'];
    $sql_complete = "UPDATE course SET complete = 1 WHERE username = ? AND courseN = ?";
    if ($stmt_complete = $conn->prepare($sql_complete)) {
        $stmt_complete->bind_param("ss", $_SESSION['name'], $courseToComplete);
        if ($stmt_complete->execute()) {
            // Redirect to refresh the page after completion
            header("Location: profile.php");
            exit();
        } else {
            echo "Error: " . $stmt_complete->error;
        }
        $stmt_complete->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch course names from database
$sql = "SELECT * FROM course WHERE username = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $_SESSION['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>InternShala | Aditya Pal</title>
  <link rel="shortcut icon" href="./assets/image/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="./assets/CSS/style.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
  <div class="container mt-4">
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-6 offset-md-3">
            <div class="card">
              <div class="card-header">
                <h3 class="text-center">Profile</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="name">Name:</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ""; ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="email">Email ID:</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ""; ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    <div class="container flex">
        <div class="row mt-5">
          <div class="col-md-6 offset-md-3">
            <div class="card" style="padding:12px">
              <div class="card-header">
                <h3 class="text-center">Courses Name</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="name">Names:</label>
                  <ul>
                  <?php foreach ($courses as $course) : ?>
                    <li style=" list-style: decimal-leading-zero;">
                        <form method="post" action="">
                            <input type="hidden" name="course_to_delete" value="<?php echo $course['courseN']; ?>">
                            <?php echo $course['courseN']; ?>&nbsp;
                            <?php if (!$course['complete']) : ?>
                                <input type="hidden" name="course_to_complete" value="<?php echo $course['courseN']; ?>">
                                <button type="submit" name="complete_course" class="btn btn-sm btn-success float-right">Complete</button>
                                <button type="submit" name="delete_course" class="btn btn-sm btn-danger float-right mr-2">Delete</button>
                            <?php else: ?>
                                <span class="text-success">Completed</span>
                            <?php endif; ?>
                        </form>
                    </li>
                  <?php endforeach; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
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
