<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "cries"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Database connection error. Please try again later.");
}

// Fetch quizzes from the database
$sql = "SELECT q.id AS quiz_id, q.title, q.topic, q.description, q.date, q.duration, u.user_name AS created_by 
        FROM quizzes q 
        JOIN users u ON q.user_id = u.user_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Quizzes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Quiz ID</th>
                <th>Title</th>
                <th>Topic</th>
                <th>Description</th>
                <th>Date</th>
                <th>Duration (min)</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["quiz_id"]) . "</td>
                            <td>" . htmlspecialchars($row["title"]) . "</td>
                            <td>" . htmlspecialchars($row["topic"]) . "</td>
                            <td>" . htmlspecialchars($row["description"]) . "</td>
                            <td>" . date('Y-m-d H:i', strtotime($row["date"])) . "</td>
                            <td>" . htmlspecialchars($row["duration"]) . "</td>
                            <td>" . htmlspecialchars($row["created_by"]) . "</td>
                            <td>
                                <a href='edit.php?id=" . htmlspecialchars($row["quiz_id"]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete.php?id=" . htmlspecialchars($row["quiz_id"]) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this quiz?\");'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No quizzes found</td></tr>";
            }
            ?>
        </tbody>
    </ table>
</div>
</body>
</html>