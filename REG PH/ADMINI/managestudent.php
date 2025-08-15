<?php
include("institute.php");
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: Adminlogin.php");
    exit();
}

$result = $conn->query("SELECT * FROM students_regestration");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Students</title>
</head>
<body>
    <h2>Students List</h2>
    <a href="managestudent.php">Add Student</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['phone']; ?></td>
            <td><?= $row['course']; ?></td>
            <td>
                <a href="edit_student.php?id=<?= $row['id']; ?>">Edit</a>
                <a href="delete_student.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
