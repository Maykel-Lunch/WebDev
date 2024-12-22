<?php
session_start();
// Database connection
$host = 'localhost'; // Change if necessary
$db = 'cries';
$user = 'root'; // Change if necessary
$pass = ''; // Change if necessary

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User ';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard : Inquizitive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> <!-- Link to Google Fonts -->
    <style>
        body {
            font-family: 'Libre Baskerville', serif; 
            background-color:rgb(238, 251, 255);
        }
        .card {
            height: 200px;
            transition: transform 0.2s;
            padding: 10px;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
     <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-secondary">
        <div class="container">
            <a class="navbar-brand text-white" href="dashboard.php">Inquisitive</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6 d-flex justify-content-center mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Create a quiz</strong></h5>
                        <p class="card-text">Design your own quiz by selecting questions and setting time limits—make it your way.</p>
                        
                        <a href="create_quiz.php" class="btn btn-primary">Create your own quiz</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex justify-content-center">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Take a quiz</strong></h5>
                        <p class="card-text">Test your knowledge with a fun quiz made by other users and find out just how much you really know!</p>
                        <a href="quiz.php" class="btn btn-primary">Take a quiz</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-2">
            <div class="col-md-6 d-flex justify-content-center mt-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Edit a quiz</strong></h5>
                        <p class="card-text">Edit your quiz by selecting your preferred questions and setting the quiz time duration</p>
                        
                        <a href="read.php" class="btn btn-primary">Edit your quiz</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex justify-content-center mt-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Score Reports</strong></h5>
                        <p class="card-text">View your performance across all quizzes and track your progress</p>
                        
                        <a href="user_profile.php" class="btn btn-primary">Go to your Scoreboard</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-secondary text-center text-lg-start mt-5">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>