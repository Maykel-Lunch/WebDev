<?php
session_start();
$host = 'localhost';
$dbname = 'cries'; 
$username = 'root'; 
$password = ''; 

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8
$conn->set_charset("utf8");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}


$user_id = $_SESSION['user_id'];

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User ';

// Fetch score reports with question count
$score_reports_query = "
    SELECT sh.*, q.title, COUNT(qs.id) AS question_count 
    FROM score_history sh 
    JOIN quizzes q ON sh.quiz_id = q.id 
    LEFT JOIN questions qs ON q.id = qs.quiz_id 
    WHERE sh.user_id = ? 
    GROUP BY sh.id";
$stmt = $conn->prepare($score_reports_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$score_reports_result = $stmt->get_result();

// Fetch quizzes created by the user
$quizzes_query = "SELECT * FROM quizzes WHERE user_id = ?";
$stmt = $conn->prepare($quizzes_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$quizzes_result = $stmt->get_result();

// Fetch the leaderboard data with rank
$leaderboard_query = "
    SELECT u.user_name, 
           COUNT(DISTINCT sh.quiz_id) AS quizzes_taken, 
           SUM(sh.score) AS total_score, 
           SUM(qs.question_count) AS total_questions,
           @rank := @rank + 1 AS rank
    FROM score_history sh
    JOIN quizzes q ON sh.quiz_id = q.id
    JOIN users u ON sh.user_id = u.user_id
    JOIN (
        SELECT quiz_id, COUNT(*) AS question_count
        FROM questions
        GROUP BY quiz_id
    ) qs ON q.id = qs.quiz_id
    JOIN (SELECT @rank := 0) r
    GROUP BY sh.user_id
    ORDER BY quizzes_taken DESC, total_score DESC
    LIMIT 10";
$leaderboard_result = $conn->query($leaderboard_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Scoreboard : Inquizitive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Libre Baskerville', serif; 
            background-color:rgb(238, 251, 255);
        }
        @media (max-width: 390px) {
            h1 {
                font-size: 1.5rem; /* Adjust heading size for mobile */
            }
            .table th, .table td {
                font-size: 0.7rem; /* Adjust table font size for mobile */
                padding: 0.5rem; /* Adjust padding for better spacing */
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
        <h1 class="mb-4 text-center"><?php echo htmlspecialchars($user_name); ?>'s Scores Report</h1>

        <!-- Card for the graph -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title text-center">Score Chart</h5>
                <div class="mb-4">
                    <canvas id="scoreChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Card for the table -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Scores Table</h5>
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Score</th>
                            <th>Date and Time Taken</th>
                            <th>Number of Questions</th> <!-- New Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $score_reports_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['score']); ?></td>
                                <td><?php echo date('m/d/y h:i A', strtotime($row['time_taken'])); ?></td>
                                <td><?php echo htmlspecialchars($row['question_count']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <!-- Card for the leaderboard -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title text-center">Leaderboard</h5>
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Rank</th> <!-- New Column for Rank -->
                            <th>User Name</th>
                            <th>Quizzes Taken</th>
                            <th>Total Score</th>
                            <th>Total Questions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $leaderboard_result->fetch_assoc()): ?>
                            <tr style="<?php echo (htmlspecialchars($row['user_name']) === htmlspecialchars($user_name)) ? 'background-color: #cce5ff; font-weight: bold;' : ''; ?>">
                                <td class="text-center" style="font-weight: bold; color: #007bff;"><?php echo htmlspecialchars($row['rank']); ?></td> <!-- Highlighted Rank -->
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['quizzes_taken']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_score']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_questions']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src=" https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const labels = [];
        const scores = [];
        const questionCounts = []; // New array for number of questions

        <?php
        // Reset the result pointer to fetch data again
        $score_reports_result->data_seek(0); // Reset the result set pointer

        while ($row = $score_reports_result->fetch_assoc()) {
            echo "labels.push('" . htmlspecialchars($row['title']) . "');";
            echo "scores.push(" . (int)$row['score'] . ");"; // Ensure score is an integer
            echo "questionCounts.push(" . (int)$row['question_count'] . ");"; // Push question count
        }
        ?>

        const ctx = document.getElementById('scoreChart').getContext('2d');
        const scoreChart = new Chart(ctx, {
            type: 'bar', // Change to line chart
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Quiz Scores',
                        data: scores,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false // Do not fill under the line
                    },
                    {
                        label: 'Number of Questions',
                        data: questionCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false // Do not fill under the line
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Scores / Number of Questions'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Quizzes'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>