<?php
include "conn.php";
session_start();

// Database connection settings
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "institute";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $RegistrationNumber = trim($_POST['RegistrationNumber']);
    $password = trim($_POST['password']);
    $error = "";  // Initialize error message variable

    if (empty($RegistrationNumber) || empty($password)) {
        $error = "Both username and password are required.";
    } else {
        // Prepare the query to prevent SQL injection
        $stmt = $conn->prepare("SELECT RegistrationNumber, password FROM students_regestration WHERE RegistrationNumber = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $RegistrationNumber);  // Bind the username parameter
        $stmt->execute();

        // Check for errors during query execution
        if ($stmt->error) {
            die("Execute failed: " . $stmt->error);
        }

        // Store the result and check if the username exists
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Bind the result to variables (username and password)
            $stmt->bind_result($dbRegistrationNumber, $dbpasswordHash);  // Bind only the needed columns (User_name and password)

            // Fetch the result
            while ($stmt->fetch()) {
                // Check if the entered password matches the stored password hash
                if (password_verify($password, $dbpasswordHash)) {
                    $_SESSION['RegistrationNumber'] = $dbRegistrationNumber;  // Store username in session
                    session_regenerate_id(true); // Regenerate session ID for security
                    header("Location: daaash.php");  // Redirect to the dashboard
                    exit();
                } else {
                    $error = "Invalid username or password.";
                }
            }
        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-image: url("student.jpg");
            background-size: cover;
            background-position: center;
        }
        form {
            padding: 1.5rem;
            background-color: #fff;
            box-shadow: 0 5px 6px rgba(3, 0, 0, 0.1);
            border-radius: 12px;
            width: 350px;
        }
        button {
            background-color: rgb(56, 172, 172);
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            border: none;
            color: white;
            font-size: 16px;
        }
        button:hover {
            background-color: rgb(46, 152, 152);
            cursor: pointer;
        }
        input {
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
        .link {
            text-align: center;
            margin-top: 10px;
            text-decoration: underline;
        }
        .link a:hover {
            color: rgb(46, 152, 152);
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .form-container h1 {
            color: #fff;
            
        }
    </style>
</head>
<body>
    <div class="form-container">
        <nav class="top"><h1>STUDENTS MANAGEMENT SYSTEM</h1></nav>
        <form method="POST" action="">
            <div class="form">
                <h2 style="text-align: center;">LOGIN FORM</h2>

                <!-- Display error message if there is an error -->
                <?php if (!empty($error)) { ?>
                    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <?php } ?>

                <input type="text" name="RegistrationNumber" placeholder="Enter username" required>
                <input type="password" name="password" placeholder="Enter Password" required>

                <button type="submit">Login</button>

                <div class="link">
                    <p><a href="reset_password.php">Recover password</a></p>
                </div>
                <p style="text-align: center;">Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>