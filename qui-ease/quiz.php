<?php
session_start();
$host = 'localhost'; 
$db = 'cries';
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Fetch quizzes from the database with additional information
$stmt = $pdo->query("
    SELECT q.*, u.user_name, COUNT(quest.id) AS question_count 
    FROM quizzes q
    JOIN users u ON q.user_id = u.user_id
    LEFT JOIN questions quest ON q.id = quest.quiz_id
    GROUP BY q.id
");
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Quizzes : Inquizitive</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> <!-- Link to Google Fonts -->
    <style>
        body {
            font-family: 'Libre Baskerville', serif; 
            background-color:rgb(238, 251, 255);
            margin: 0;
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
        <h1 class="text-center">Available Quizzes</h1>
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Created By</th>
                        <th>Duration (minutes)</th>
                        <th>Number of Questions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td><?= htmlspecialchars($quiz['title']) ?></td>
                            <td><?= htmlspecialchars($quiz['user_name']) ?></td>
                            <td><?= htmlspecialchars($quiz['duration']) ?></td>
                            <td><?= htmlspecialchars($quiz['question_count']) ?></td>
                            <td>
                                <a href="take_quiz.php?id=<?= $quiz['id'] ?>" class="btn btn-primary btn-sm">Take Quiz</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
    
    <footer class="bg-secondary text-center text-lg-start mt-5">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>
    
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>