<?php
include"conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head><link rel="stylesheet" href="registration.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRATION FORM</title>
    <style>
     *{
        margin: 0;
        padding:0;
        box-sizing: border-box;
        font-family: "p0ppins",sans-serif;
    }
    
    
    body{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
form{
    padding: 20px;
    background-color: rgb(255, 255, 255);
    background-color: rgb(255, 255, 255);
    box-shadow:0 1px 3px rgb(4,2,0,1);
    border-radius: 12px;
    width: 450px;
}

.h2{
    color: rgb(5, 5, 5);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: -12px;
    width: 420px;
    height: 40px;
}
.buttoni{
    border-radius: 30px;
    padding: 1px;
    width: 60px;
}
input[type="text"],
input[type="email"],
input[type="password"],
input[type="number"],
input[type="Address"],
input[type="varch"],
textarea{
    padding: 10px;
    border: 1px solid #393b3b;
    background-color:hwb(240 99% 1% / 0.836);
    margin-bottom: -10px;
    width: 90%;
    border-radius:10px;
}
title{
    color: #000000;
}

select{
    padding: 10px;
    width: 90%;
    border-radius: 10px;
    cursor: pointer;
}

.gender {

    width: 90%;
    border-radius: 10px;
    padding: 10px;
    cursor:pointer;       
}
input[type="submit"]:hover{
        background-color: rgba(21, 255, 0, 0.826);
    }
    input[type="submit"]{
        border-radius: 10px;
        padding: 10px;
        width: 360px;
        background-color: #1ecd59;
    }
    
</style>
</head>
<body> 
    <div class="container">
    <form action="connect.php" method="POST">
        <div class="h2">
        <h2>REGISTRATION FORM</h2>
    </div>
<div class="inputs">                     <!--names-->
            <input type="text" placeholder="Enter your full name"name="Full_name" required><br><br>
</div>
                    <div class="contacts">                   <!--contacts-->
                        <input type="number" placeholder="Enter your phone number"name="phone_number"required><br><br>
                        <input type="email"placeholder="Enter your Email"name="Email"required><br><br>
                        <input type="text"placeholder="Enter your home Adress"name="Address" required><br><br>
                        <input type="text"placeholder="Enter your form four index number"name="Form_IV_number" required><br><br>     
                    </div>
<div class="passwpord">                    <!--password--> 
        <input type="password" placeholder="Enter your Password" name="Password"required><br><br>
        <input type="password"placeholder="Confirm Password" name="Confirm_password"required><br><br> 
</div>                            <!--courses selection-->
<div class="c-selection">
    <label for="courses"></label>
        <select id="Courses" name="Courses" required>
                <option value="disabbled select">select your cousrse</option>
                <option value="Bcs">bachelor of computer science</option>
                <option value="IT">bachelor of information technology</option> 
                <option value="BAF">bachelor of accountancy and finance</option>   
                <option value="BA">bachelor of accountancy </option> 
                <option value="bcs">diploma in computer science</option> 
                <option value="bcs">diploma in information technology</option> 
                <option value="bc">diploma in accountancy and finance</option><br>
        </select>
</div>               <!--gender selection-->
<div class="gender">
    <input type="radio" name="gender"value="male"><lebel>male</lebel>
    <input type="radio" name="gender"value="female"> <lebel>female</lebel>
</div>
<div class="buttoni">
    <input type="submit" value="register">
    <?php

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

</div>
    </form>
    </div>
</body>
</html>
    
