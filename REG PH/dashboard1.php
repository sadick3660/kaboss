<?php
session_start();
include 'conn.php';
// Redirect to login if not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location:login.php");
    exit;
}
// Database connection
$conn = new mysqli("localhost", "root", "", "institute");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch students for Manage Students section
$students = $conn->query("SELECT id, Full_name, Email FROM students_regestration");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #333;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #444;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
        }
        .sidebar button {
            display: block;
            width: 100%;
            border: none;
            background: none;
            color: white;
            padding: 10px 20px;
            text-align: left;
            font-size: 16px;
            cursor: pointer;
        }
        .sidebar button:hover {
            background-color: #575757;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
            background-color: #f4f4f9;
            min-height: 100vh;
        }
        .card {
            display: none;
            background: white;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card.active {
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
</header>
<div class="sidebar">
<img src="IAA.jpg" alt="IAA"width="200" height="200">
    <button onclick="showSection('manage-students')">Manage Students</button>
    <a href="Upload_result.php">Upload result</a><br>
    <a href="Edit_result.php">Edit result</a><br>
    <button onclick="showSection('change-password')">Change Password</button>
    <button onclick="logout()">Logout</button>
</div>
<div class="main-content">
    <!-- Manage Students -->
    <div class="card" id="manage-students">
        <h3>Manage Students</h3>
        <?php if ($students->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                    <a href="edit_student.php?id=<?= $row['id'] ?>">Edit</a> |
                                    <a href="delete_student.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found.</p>
        <?php endif; ?>
    </div>
<div>
    <ul>
        <li><a href="Upload_result.php">Upload result</a></li>
        <li><a href="Edit_result.php">Edit result</a></li>
    </ul>
    <!-- Change Password -->
    <div class="card" id="change-password">
        <h3>Change Password</h3>
        <form action="change_password.php" method="POST">
            <label for="current-password">Current Password:</label><br>
            <input type="password" id="current-password" name="current_password" required><br><br>
            <label for="new-password">New Password:</label><br>
            <input type="password" id="new-password" name="new_password" required><br><br>
            <button type="submit">Change Password</button>
        </form>
    </div>
</div>
<script>
    function showSection(sectionId) {
        // Hide all cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => card.classList.remove('active'));
        // Show the selected card
        const selectedCard = document.getElementById(sectionId);
        if (selectedCard) {
            selectedCard.classList.add('active');
        }
    }
    function logout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "?logout=true";
        }
    }
</script>
</body>
</html>
