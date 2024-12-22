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

// Get the logged-in user's ID
$current_user_id = $_SESSION['user_id'];

// Fetch quizzes created by the logged-in user from the database
$sql = "SELECT q.id AS quiz_id, q.title, q.topic, q.description, q.date, q.duration, u.user_name AS created_by 
        FROM quizzes q 
        JOIN users u ON q.user_id = u.user_id
        WHERE q.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
if ($success_message) {
    // Clear the message after displaying it
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes : Inquizitive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> <!-- Link to Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Link to Font Awesome -->
    <style>
       html, body {
            height: 100%; /* Ensure the body takes the full height */
        }
        body {
            display: flex;
            flex-direction: column; /* Stack children vertically */
            font-family: 'Libre Baskerville', serif; 
            background-color:rgb(238, 251, 255);
        }
        footer {
            margin-top: auto; /* Push footer to the bottom */
        }
        @media (max-width: 390px) {
            h1 {
                font-size: 1.5rem; /* Adjust heading size for mobile */
            }
            .table th, .table td {
                font-size: 0.7rem; /* Adjust table font size for mobile */
                padding: 0.5rem; /* Adjust padding for better spacing */
            }
            .btn-sm{
                font-size: 0.5rem; /* Smaller button size for mobile */
            }
        }
    </style>
</head>
<body>
    <!-- Success Message -->
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-secondary">
        <div class="container">
            <a class="navbar-brand text-white" href="dashboard.php">Inquizitive</a>
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
        <h2 class="text-center">Your Quizzes</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>Title</th>
                        <th>Topic</th>
                        <th>Date</th>
                        <th>Duration (min)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr class='text-center'>
                                    <td>" . htmlspecialchars($row["title"]) . "</td>
                                    <td>" . htmlspecialchars($row["topic"]) . "</td>
                                    <td>" . date('m/d/y h:i A', strtotime($row["date"])) . "</td>
                                    <td>" . htmlspecialchars($row["duration"]) . "</td>
                                    <td>
                                        <a href='edit.php?id=" . htmlspecialchars($row["quiz_id"]) . "' class='btn btn-secondary btn-sm' title='Edit'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <a href='delete.php?id=" . htmlspecialchars($row["quiz_id"]) . "' class='btn btn-danger btn-sm' title='Delete' onclick='return confirm(\"Are you sure you want to delete this quiz?\");'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No quizzes found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
    
    <footer class="bg-secondary text-center text-lg-start">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
