<?php
if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, and if so, redirect to dashboard.php
if (isset($_SESSION['patient_id'])) {
    header("Location: http://localhost/project/dashboard/index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database (update with your database details)
    $db_username = "root";
    $db_password = ""; // Assuming an empty password, change it if you have a password

    $conn = new mysqli("localhost", $db_username, $db_password, "center");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $patient_id = $_POST['patient_id'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT patient_id, password FROM patients WHERE patient_id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $stmt->store_result();

    // If a matching user is found, verify the password and set user information in session
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($found_patient_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['patient_id'] = $found_patient_id;

            $stmt->close();
            $conn->close();

            header("Location: http://localhost/project/dashboard/index.html");
            exit();
        } else {
            $_SESSION['error'] = "Invalid credentials. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Invalid credentials. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Prevents scrolling on smaller screens */
            font-family: Arial, sans-serif; /* Set a default font family */
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
        }

        .navbar {
            background-color: transparent !important; /* Make the navbar background transparent */
            margin-bottom: 20px;
            padding: 20px 0; /* Increased padding */
        }

        .navbar-collapse {
            justify-content: center;
        }

        .navbar-nav .nav-item .nav-link {
            color: white !important;
            font-size: 18px; /* Increased font size */
            margin: 10px; /* Increased margin */
        }

        .login-box {
            max-width: 400px;
            padding: 20px;
            border-radius: 5px;
            z-index: 1;
            background: none; /* Remove the background */
            box-shadow: none; /* Remove the box shadow */
        }

        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-logo a {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: white;
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
    <!-- Background Video -->
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

    <!-- Login Box -->
    <div class="login-box">
        <div class="login-logo">
            <a href="./index.php"><b>Patient</b> Login</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to check your results</p>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name='patient_id' class="form-control" placeholder="Patient ID" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name='password' class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            <?php 
                            if (isset($_SESSION['error']) and !empty($_SESSION['error'])) {
                                $msg = "<div class='alert alert-danger'><strong>Error!</strong> ".$_SESSION['error']."</div>";
                                echo $msg;
                                $_SESSION['error'] = "";
                            }
                            ?> 
                        </div>
                        <div class="col-12">
                            <a href="register.php" class="btn btn-secondary btn-block">Signup</a>
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
    <script>
</body>

</html>
