<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "institute");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $RegistrationNumber = $_POST['RegistrationNumber'];
    $Password = $_POST['Password'];

    // Validate input
    if (empty($RegistrationNumber) || empty($Password)) {
        $error = "Please fill in all fields.";
    } else {
        // Query the database for the student
        $sql = "SELECT * FROM students_regestration WHERE RegistrationNumber = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $RegistrationNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $student = $result->fetch_assoc();
            // Verify password
            if (password_verify($Password, $student['Password'])) {
                // Set session variables
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['Full_name'] = $student['Full_name'];

                // Redirect to student dashboard
                header("Location: daaash.php");
                exit;
            } else {
                $error = "Invalid registration number or password.";
            }
        } else {
            $error = "No account found with the provided registration number.";
        }
    }
}
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
            background-image: url("salim.jpg");
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1 style="text-align: center;">RESULT SYSTEM</h1>
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
        
        <?php
        if (isset($error)) {
            echo '<div class="error-message">' . $error . '</div>';
        }
        ?>
    </div>
</body>
</html>