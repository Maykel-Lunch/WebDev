<?php
// quiz_results.php

// Ensure that the required variables are available
if (!isset($quiz) || !isset($score) || !isset($questions)) {
    die("Required data not available.");
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Quiz Results : Inquizitive</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> 
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
        /* Responsive canvas */
        #scoreChart {
            width: 50px; /* Full width */
            height: 50px; /* Auto height */
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

    <div class='container mt-5'>
        <h1 class='text-center'>Quiz Results</h1>
        <div class='card mt-4'>
            <div class='card-body'>
                <h5 class='card-title'>Quiz Title: <?= htmlspecialchars($quiz['title']) ?></h5>
                <p class='card-text'>Your Score: <strong><?= $score ?></strong> out of <strong><?= count($questions) ?></strong></p>
                
                <!-- Canvas for Pie Chart -->
                <canvas id='scoreChart' class="m-5"></canvas>
                
            </div>
            <a href='quiz.php' class='btn btn-primary m-2'>Back to Quizzes</a>
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

    <script>
        // Prepare data for the pie chart
        const correctAnswers = <?= $score ?>;
        const incorrectAnswers = <?= count($questions) - $score ?>;
        
        const ctx = document.getElementById('scoreChart').getContext('2d');
        const scoreChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Correct Answers', 'Incorrect Answers'],
                datasets: [{
                    label: 'Score Distribution',
                    data: [correctAnswers, incorrectAnswers],
                    backgroundColor: ['#4CAF50', '#F44336'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Score Distribution'
                    }
                }
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src=" https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>