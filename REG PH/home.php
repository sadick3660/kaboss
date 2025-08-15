<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: black;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Auth Buttons */
        .auth-buttons .btn {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .auth-buttons .btn:hover {
            background-color: #005f73;
        }

        /* Main Content */
        main {
            padding: 20px;
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="dropdown">
                <button class="dropbtn">Menu</button>
                <div class="dropdown-content">
                    <a href="Adminlogin.php">Admin</a>
                    <a href="daaash.php">Student</a>
                </div>
            </div>
            <div class="auth-buttons">
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            </div>
        </div>
    </header>

    <main>
        <h1><b>STUDENT'S MANAGEMENT SYSTEM</></h1>
        <p><B>INSTITUTE OF ACCOUNTANCY ARUSHA DAR-ES-SALAAM COMPUS</B>.</p>
    </main>

    <script>
        // Add any interactive JavaScript functionality here if needed
        // For example, you can add event listeners for the dropdown menu or buttons.

        document.addEventListener("DOMContentLoaded", function () {
            console.log("Page loaded!");
        });
    </script>
</body>
</html>