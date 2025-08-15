<?php
// Database connection (update with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$database = "institute";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PHP logic for registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Full_name = $_POST['Full_name'];
    $phone_number = $_POST['phone_number'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];
    $Password = $_POST['Password'];
    $Confirm_password = $_POST['Confirm_password'];
    $gender = $_POST['gender'];
    $Courses = $_POST['Courses'];
    $Form_IV_number = $_POST['Form_IV_number'];

    // Basic validation
    if (empty($Full_name) || empty($phone_number) || empty($Email) || empty($Address) || empty($Courses) || empty($Form_IV_number) || empty($Password) || empty($Confirm_password)) {
        $error = "All fields are required.";
    } elseif ($Password !== $Confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Generate unique registration number
        $year = date("Y");
        $randomNumber = rand(2000, 9999); // Random 4-digit number
        $RegistrationNumber = strtoupper($Courses) . "/02/" . $randomNumber . "/" . $year;

        // Hash the password for security
        $hashedPassword = Password_hash($Password, PASSWORD_BCRYPT);

        // Save data to the database
        $stmt = $conn->prepare("INSERT INTO students_regestration (Full_name, phone_number, Email, Address, RegistrationNumber, Password, gender, Courses, Form_IV_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Check if prepare failed and display the error
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt->bind_param("sssssssss", $Full_name, $phone_number, $Email, $Address, $RegistrationNumber, $hashedPassword, $gender, $Courses, $Form_IV_number);
        if ($stmt->execute()) {
            echo "Registration successful! Your Registration Number is: <strong> $RegistrationNumber </strong>";
            echo "<br>Use this Registration Number as your username and password to log in.";
            // Redirect to dashboard or login page (replace with actual login/dashboard file)
            header("refresh:5;url=login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
