<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_name'])) {
    header("Location: dashboard.php");
    exit();
}

$student_id = $_SESSION['user_name'];
$query = "SELECT * FROM students WHERE id='$student_id'";
$result = $conn->query($query);
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= $student['name']; ?></h2>
    <img src="uploads/<?= $student['photo']; ?>" alt="Profile Photo" width="100"><br>
    <p>Exam Number: <?= $student['exam_number']; ?></p>
    <a href="upload_photo.php">Upload Photo</a> |
    <a href="view_results.php">View Results</a> |
    <a href="payment.php">Make Payment</a> |
    <a href="change_password.php">Change Password</a> |
    <a href="logout.php">Logout</a>
</body>
</html>
8.Upload photos 
<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $student_id = $_SESSION['student_id'];
    $photo_name = time() . "_" . $_FILES['photo']['name'];
    $target = "uploads/" . $photo_name;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $conn->query("UPDATE students SET photo='$photo_name' WHERE id='$user_name'");
        echo "Photo uploaded successfully!";
    } else {
        echo "Upload failed.";
    }
}
?>
