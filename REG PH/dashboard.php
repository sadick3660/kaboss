<?php
session_start();
include('conn.php');

// Check if the user is logged in
if (!isset($_SESSION['User_name'])) {
    // Redirect to the login page if not authenticated
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location:login.php.php?message=LoggedOut");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #fdf8f8;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Dashboard Layout */
        .dashboard {
            display: flex;
            width: 100%;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #0e549b;
            color:white;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items:;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .sidebar nav ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }
        
        .sidebar nav ul li {
            margin: 20px 0;
        }
        
        .sidebar nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .sidebar nav ul li a.active,
        .sidebar nav ul li a:hover {
            background-color: #16a78a;
        }
        
        /* Main Content */
        main {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            padding: 100px;
        }
        /* Cards Section */
        .sum{
            display: flex;
            gap: 10px;
            flex-wrap: wrap;   
        }
        
        .sum{
            background-color: rgb(77, 6, 6);
            flex: 1;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(170, 32, 32, 0.1);
            min-width: 200px;
            text-align: center;
        }
        
        .sum h1 {
            font-size: 32px;
            color: #f5f4f4;
            margin-bottom: 10px;
        }
        
        
        </style>
</head>
<body>
        <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Dashboard</h2>
            
            <nav>
                <ul>
                    <li><a href="upload.php">Edit your photo</a></li>
                    <li><a href="payment">payments</a></li>
                    <li><a href="exam_number">Examination number</a></li>
                    <li><a href="result">Result</a></li>
                    <li><a href="password_reset">Change password</a></li>
                    <li><a href="login.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main>

        </main>
    </div>
        
</body>
</html>
