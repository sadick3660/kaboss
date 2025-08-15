<?php
session_start();
include('conn.php');

// Enable error reporting for MySQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the student is logged in
if (empty($_SESSION["RegistrationNumber"])) {
    header("Location: login.php");
    exit();
}

$RegistrationNumber = $_SESSION["RegistrationNumber"];

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM students_regestration WHERE RegistrationNumber = ?");
$stmt->bind_param("s", $RegistrationNumber);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    die('No student found with this ID');
}

// Fetch student results for both semesters
$stmt_results1 = $conn->prepare("SELECT * FROM results WHERE RegistrationNumber = ? AND semester = 'Semester 1'");
$stmt_results1->bind_param("s", $RegistrationNumber);
$stmt_results1->execute();
$results1 = $stmt_results1->get_result();

$stmt_results2 = $conn->prepare("SELECT * FROM results WHERE RegistrationNumber = ? AND semester = 'Semester 2'");
$stmt_results2->bind_param("s", $RegistrationNumber);
$stmt_results2->execute();
$results2 = $stmt_results2->get_result();

$stmt_results1->close();
$stmt_results2->close();

$page = $_GET['page'] ?? 'home';

// Function to generate exam number
function generateExamNumber($registrationNumber) {
    // Example: Combine the registration number with the current year and a random number for uniqueness
    $examNumber = strtoupper($registrationNumber) . '-' . date('Y') . '-' . rand(1000, 9999);
    return $examNumber;
}

$examNumber = generateExamNumber($RegistrationNumber);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #f4f4f4;
        }
        .dashboard {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            background: #34495e;
            color: white;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: 0.3s;
            border-radius: 5px;
            font-size: 16px;
        }
        .sidebar button i {
            margin-right: 10px;
        }
        .sidebar button:hover {
            background: #1abc9c;
            transform: translateX(5px);
        }
        .content {
            flex: 1;
            padding: 20px;
            background: #ecf0f1;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }

        /* Styles for the Change Password Form */
        .change-password-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            margin: auto;
        }
        .change-password-form label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        .change-password-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .change-password-form button {
            width: 100%;
            padding: 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .change-password-form button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            
            <h2 style="color: white;">Dashboard</h2>
            <?php
            $menuItems = [
                'home' => ['Home', 'fa-home'],
                'upload_photo' => ['Upload Photo', 'fa-upload'],
                'results' => ['Results', 'fa-chart-line'],
                'exam_number' => ['Exam Number', 'fa-id-badge'],
                'reset_password' => ['Change Password', 'fa-key'],
                'logout' => ['Logout', 'fa-sign-out-alt']
            ];
            foreach ($menuItems as $key => $item) {
                if ($key == 'logout') {
                    echo "<button onclick=\"location.href='logout.php'\" style='background: #e74c3c;'><i class='fas {$item[1]}'></i> {$item[0]}</button>";
                } else {
                    echo "<button onclick=\"location.href='daaash.php?page=$key'\"><i class='fas {$item[1]}'></i> {$item[0]}</button>";
                }
            }
            ?>
        </div>
        <div class="content">
            <h1>Welcome, <?php echo htmlspecialchars($student['Full_name']); ?>!</h1>

            <?php if ($page == 'home'): ?>
                <h2>Home</h2>
                <p>Welcome to your student dashboard.</p>
            <?php endif; ?>

            <?php if ($page == 'exam_number'): ?>
                <h2>Your Exam Number</h2>
                <p><strong>Exam Number:</strong> <?php echo $examNumber; ?></p>
                <p>This exam number is generated based on your registration number and current year.</p>
            <?php endif; ?>

            <?php if ($page == 'results'): ?>
                <h2>Your Results</h2>
                <?php if ($results1->num_rows > 0): ?>
                    <h3>Semester One</h3>
                    <table>
                        <thead>
                            <tr><th>SUBJECT</th><th>COURSE WORK</th><th>GRADE</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $results1->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['coursework1']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject1']); ?></td>
                                    <td><?php echo (in_array(strtoupper($row['subject1']), ["A", "B", "C"])) ? "PASS" : "FAIL"; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if ($results2->num_rows > 0): ?>
                    <h3>Semester Two</h3>
                    <table>
                        <thead>
                            <tr><th>SUBJECT</th><th>COURSE WORK</th><th>GRADE</th><th>CREDITS</th><th>STATUS</th></tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $results2->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['coursework1']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject1']); ?></td>
                                    <td><?php echo (in_array(strtoupper($row['subject1']), ["A", "B", "C"])) ? "PASS" : "FAIL"; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <div class="GPA">
                        <?php
function calculateGPA($courses) {
    $totalGradePoints = 0;
    $totalCreditHours = 0;

    // Tanzanian grading system (adjust as needed)
    $gradeScale = [
        "A"  => 5.0,
        "B+" => 4.0,
        "B"  => 3.0,
        "C"  => 2.0,
        "D"  => 1.0,
        "F"  => 0.0
    ];

    foreach ($courses as $course) {
        $grade = strtoupper($course['grade']); // Convert to uppercase
        $creditHours = $course['credit_hours'];

        if (isset($gradeScale[$grade])) {
            $gradePoint = $gradeScale[$grade];
            $totalGradePoints += ($gradePoint * $creditHours);
            $totalCreditHours += $creditHours;
        } else {
            echo "Invalid grade: " . $grade . "<br>";
            return false;
        }
    }

    // Prevent division by zero
    if ($totalCreditHours == 0) {
        return 0;
    }

    // Calculate GPA
    $gpa = $totalGradePoints / $totalCreditHours;
    return number_format($gpa, 2); // Format to 2 decimal places
}

// Example courses array (this data can come from a database)
$courses = [
    ["grade" => "A", "credit_hours" => 3],
    ["grade" => "B+", "credit_hours" => 3],
    ["grade" => "B", "credit_hours" => 2],
    ["grade" => "C", "credit_hours" => 2]
];

$gpa = calculateGPA($courses);
echo "Your GPA is: " . $gpa;
?>

                        </div>
                    </table>
                <?php else: ?>
                    <p>No results available.</p>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($page == 'reset_password'): ?>
                <h2>Change Password</h2>
                <form method="POST" action="change_password.php" class="change-password-form">
                    <label>Current Password:</label>
                    <input type="password" name="current_password" required><br>

                    <label>New Password:</label>
                    <input type="password" name="new_password" required><br>

                    <label>Confirm New Password:</label>
                    <input type="password" name="confirm_password" required><br>

                    <button type="submit">Change Password</button>
                </form>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>
