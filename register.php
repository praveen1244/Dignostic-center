<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Registration</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

  <!-- Custom CSS -->
  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 100vh;
      margin: 0; /* Reset margin to ensure full height */
      overflow: hidden; /* Prevents scrolling on smaller screens */
    }
    video {
      position: fixed; /* Keep the video fixed in the background */
      top: 0;
      left: 0;
      min-width: 100%;
      min-height: 100%;
      z-index: -1;
    }
    .navbar {
      background-color: transparent !important; /* Make the navbar background transparent */
      padding: 20px 0; /* Increased padding */
      width: 100%; /* Full width */
      position: absolute; /* Absolute positioning */
      top: 0; /* Position at the top */
      left: 0; /* Align with the left edge */
      z-index: 1; /* Ensure it's above other elements */
    }
    .navbar-collapse {
      justify-content: center;
    }
    .navbar-nav .nav-item .nav-link {
      color: black !important;
      font-size: 18px; /* Increased font size */
      margin: 10px; /* Increased margin */
    }
    .login-box {
      max-width: 400px;
      padding: 20px;
      border-radius: 5px;
      margin-top: auto; /* Move to the bottom */
      margin-bottom: auto; /* Move to the top */
    }
    .login-logo {
      text-align: center;
    }
    .login-logo a {
      font-size: 24px;
      font-weight: bold;
      color: #007BFF;
      text-decoration: none;
    }
    .login-card-body {
      text-align: center;
    }
    .login-box-msg {
      font-size: 18px;
      font-weight: bold;
    }
  </style>
</head>

<body>
<video src="images/blood.mp4" autoplay muted loop></video>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="bs-example-navbar-collapse-1">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.html"><span style="color: white;"
                            class="menu__helper">Home</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php"><span style="color: white;"
                            class="menu__helper">About Us</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span style="color: white;" class="menu__helper">Book your medical test</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="package.html" style="color: red;">Book Your Health Package</a></li>
                        <li><a href="test.html" style="color: red;">Book Your Test</a></li>
                        <li><a href="collect_samples.html" style="color: red;">Collect Samples from Home</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/project/login.php"><span style="color: white;"
                            class="menu__helper">Login</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php"><span style="color: white;"
                            class="menu__helper">Contact Us</span></a>
                </li>
            </ul>
        </div>
    </nav>

  <div class="login-box">
    <div class="login-logo">
      <a href="./index.php"><b>Patient</b> Registration</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Register as a new patient</p>

        <form action="register.php" method="post"> <!-- Change action to register.php -->
          <?php 
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              // Connect to the database (update with your database details)
              $db_username = "root";
              $db_password = ""; // Assuming an empty password, change it if you have a password

              $conn = new mysqli("localhost", $db_username, $db_password, "center");

              // Check connection
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }

              $patient_name = $_POST['patient_name'];
              $patient_id = $_POST['patient_id'];
              $password = $_POST['password'];

              // Hash the password before storing it
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);

              // Use prepared statements to prevent SQL injection
              $stmt = $conn->prepare("INSERT INTO patients (patient_name, patient_id, password) VALUES (?, ?, ?)");
              $stmt->bind_param("sss", $patient_name, $patient_id, $hashed_password);

              if ($stmt->execute()) {
                  $_SESSION['registration_success'] = "Registration successful. Please login.";
              } else {
                  $_SESSION['registration_error'] = "Registration failed. Please try again.";
              }

              $stmt->close();
              $conn->close();
          }
          ?>
          <div class="input-group mb-3">
            <input type="text" name="patient_name" class="form-control" placeholder="Patient Name" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="patient_id" class="form-control" placeholder="Patient ID" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-id-card"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
              <?php 
              if (isset($_SESSION['registration_error']) and !empty($_SESSION['registration_error'])) {
                $msg = "<div class='alert alert-danger'><strong>Error!</strong> ".$_SESSION['registration_error']."</div>";
                echo $msg;
                $_SESSION['registration_error'] = "";
              }
              if (isset($_SESSION['registration_success']) and !empty($_SESSION['registration_success'])) {
                $msg = "<div class='alert alert-success'><strong>Success!</strong> ".$_SESSION['registration_success']."</div>";
                echo $msg;
                $_SESSION['registration_success'] = "";
                echo "<script>setTimeout(function(){window.location.href='login.php';}, 2000);</script>";
              }
              ?> 
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
