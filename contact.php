<?php
// Define database connection parameters
$host = "localhost"; // Replace with your database host
$dbname = "center"; // Replace with your database name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize form data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $phone = test_input($_POST["phone"]);
    $appointmentDate = test_input($_POST["appointmentDate"]);
    $selectService = test_input($_POST["selectService"]);
    $message = test_input($_POST["message"]);

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO contact (name, email, phone, appointmentDate, selectService, message) VALUES (?, ?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $phone);
        $stmt->bindParam(4, $appointmentDate);
        $stmt->bindParam(5, $selectService);
        $stmt->bindParam(6, $message);

        // Execute the statement
        $stmt->execute();

        // Redirect to the thank you page or display a success message
        header("Location: thank_you.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Diagnostic Center</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('images/ppp.jpg'); /* Add correct path to background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto; /* Center the form */
        }

        .sidebar {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        button:hover {
            background-color: #45a049;
        }

        .message-container {
            text-align: center;
            display: none;
            color: #4caf50;
            margin-top: 20px;
        }

        .navbar {
            background-color: transparent !important;
            margin-bottom: 20px;
            padding: 20px 0;
            position: absolute;
            top: 20px;
            width: 100%;
        }

        .navbar-collapse {
            justify-content: center;
        }

        .navbar-nav .nav-item .nav-link {
            color: black !important;
            font-size: 18px;
            margin: 10px;
        }
    </style>
    <script>
        function submitForm() {
            var contactForm = document.getElementById("contactForm");
            var messageContainer = document.getElementById("messageContainer");

            // Hide the form and show the message container
            contactForm.style.display = "none";
            messageContainer.style.display = "block";

            // You can add more actions here, such as sending data to a server or other functionality.

            // Simulate a delay, and then reset the form
            setTimeout(function () {
                messageContainer.style.display = "none";
                contactForm.style.display = "block";
                contactForm.reset();
            }, 3000); // Delay for 3 seconds (adjust as needed)
        }
    </script>
</head>

<body>
<div class="collapse navbar-collapse justify-content-center" id="bs-example-navbar-collapse-1">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.html"><span style="color: black;"
                            class="menu__helper">Home</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php"><span style="color: black;"
                            class="menu__helper">About Us</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span style="color: black;" class="menu__helper">Book your medical test</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="package.html" style="color: red;">Book Your Health Package</a></li>
                        <li><a href="test.html" style="color: red;">Book Your Test</a></li>
                        <li><a href="collect_samples.html" style="color: red;">Collect Samples from Home</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/project/login.php"><span style="color: black;"
                            class="menu__helper">Login</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php"><span style="color: black;"
                            class="menu__helper">Contact Us</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <!-- Main Content -->
        <div class="form-container" id="contactFormContainer">
            <h2>Contact Us - Diagnostic Center</h2>
            <p>Reach out to us for appointments, inquiries, and quality healthcare services.</p>

            <form id="contactForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                onsubmit="submitForm(); return false;">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="appointmentDate">Appointment Date:</label>
                <input type="date" id="appointmentDate" name="appointmentDate" required>

                <label for="selectService">Select Service:</label>
                <select id="selectService" name="selectService" required>
                    <option value="generalCheckup">General Checkup</option>
                    <option value="bloodTest">Blood Test</option>
                    <option value="healthPackages">Health Packages</option>
                    <option value="xRay">X-Ray</option>
                    <option value="ultrasound">Ultrasound</option>
                    <option value="sampleCollection">Sample Collection</option>
                    <option value="consultation">Consultation</option>
                    <option value="mri">MRI</option>
                    <option value="cardiology">Cardiology Consultation</option>
                    <option value="diabetesManagement">Diabetes Management</option>
                    <option value="physicalTherapy">Physical Therapy</option>
                    <!-- Add more options as needed -->
                </select>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Submit</button>
            </form>

            <div class="message-container" id="messageContainer">
                <h2>Thank you for contacting us! We will get back to you soon.</h2>
            </div>
        </div>
    </div>
</body>

</html>
