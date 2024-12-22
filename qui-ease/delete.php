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

// Check if the quiz ID is set and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $quiz_id = intval($_GET['id']);
    
    // First, delete related questions
    $delete_questions_sql = "DELETE FROM questions WHERE quiz_id = ?";
    $stmt = $conn->prepare($delete_questions_sql);
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $stmt->close();

    // Now, delete the quiz
    $delete_quiz_sql = "DELETE FROM quizzes WHERE id = ?";
    $stmt = $conn->prepare($delete_quiz_sql);
    $stmt->bind_param("i", $quiz_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Set a success message
        $_SESSION['success_message'] = "Quiz and its related questions deleted successfully.";
    } else {
        // Set an error message
        $_SESSION['success_message'] = "Error deleting quiz: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
} else {
    // Set an error message if the ID is not valid
    $_SESSION['success_message'] = "Invalid quiz ID.";
}

// Close the database connection
$conn->close();

// Redirect back to the quizzes page
header("Location: read.php");
exit();
?>