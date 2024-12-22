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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to take a quiz. <a href='login.php'>Login here</a>");
}

if (!isset($_GET['id'])) {
    die("Quiz ID not specified.");
}

$quiz_id = intval($_GET['id']);

// Fetch quiz details
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found.");
}

// Fetch questions for the quiz in random order
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY RAND()");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['answers'];
    $score = 0;

    foreach ($questions as $question) {
        // Ensure the correct answer is treated as a string
        $correctAnswer = (string)$question['correct_answer']; // Cast to string if necessary
        if (isset($answers[$question['id']]) && (string)$answers[$question['id']] === $correctAnswer) {
            $score++;
        }
    }

    // Insert score into the database
    $stmt = $pdo->prepare("INSERT INTO score_history (user_id, quiz_id, title, time_taken, score) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->execute([$_SESSION['user_id'], $quiz_id, $quiz['title'], $score]);

    
    include 'quiz_results.php';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($quiz['title']) ?> : Inquizitive</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> <!-- Link to Google Fonts -->
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
    </style>
    
    
</head>
<body class="d-flex flex-column min-vh-100">
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
        <h1 class="text-center"><?= htmlspecialchars($quiz['title']) ?></h1>
        <div id="timer" class="alert alert-info text-center"><?= $quiz['duration'] ?> minutes remaining</div>
        
        <!-- Card for the quiz form -->
        <div class="card">
            <div class="card-body">
                <form id="quizForm" method="POST" action="">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                    <div id="error-message" class="alert alert-danger" style="display: none;"></div> <!-- Error message div -->
                    <!-- Note about not being able to change answers -->
                    <div id="finalNote" class="alert alert-warning" style="display: none; margin-top: 20px;">
                        Once you click "Done", you will not be able to change your answers.
                    </div>
                    <?php 
                    $questionNumber = 1; // Initialize question number
                    foreach ($questions as $index => $question): ?>
                        <div class="question" id="question_<?= $index ?>" style="display: <?= $index === 0 ? 'block' : 'none' ?>;">
                            <p><?= $questionNumber . ". " . htmlspecialchars($question['text']) ?></p>
                            <?php if ($question['type'] == 'multiplechoice'): ?>
                                <?php $choices = explode(',', $question['choices']); ?>
                                <?php foreach ($choices as $choice): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" value="<?= htmlspecialchars($choice) ?>" id="choice_<?= $question['id'] . '_' . htmlspecialchars($choice) ?>" onclick="clearErrorMessage()">
                                        <label class="form-check-label" for="choice_<?= $question['id'] . '_' . htmlspecialchars($choice) ?>">
                                            <?= htmlspecialchars($choice) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif ($question['type'] == 'truefalse'): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" value="true" id="true_<?= $question['id'] ?>" onclick="clearErrorMessage()">
                                    <label class="form-check-label" for="true_<?= $question['id'] ?>">True</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" value="false" id="false_<?= $question['id'] ?>" onclick="clearErrorMessage()">
                                    <label class="form-check-label" for="false_<?= $question['id'] ?>">False</label>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php $questionNumber++; ?>
                    <?php endforeach; ?>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="backBtn" onclick="window.location.href='quiz.php'">Quiz List</button>
                        <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeQuestion(-1)" style="display: none;">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeQuestion(1)">Next</button>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="prevBtn2" onclick="changeQuestion(-1)" style="display: none;">Previous</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">Submit Quiz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentQuestionIndex = 0;
        const questions = document.querySelectorAll('.question');

        function changeQuestion(direction) {
            // Check if an answer is selected for the current question
            const currentQuestion = questions[currentQuestionIndex];
            const selectedAnswer = currentQuestion.querySelector('input[type="radio"]:checked');

            if (direction === 1 && !selectedAnswer) { // Moving to the next question
                document.getElementById('error-message').innerText = "Please select an answer before proceeding to the next question.";
                document.getElementById('error-message').style.display = 'block'; // Show error message
                return; // Prevent moving to the next question
            } else {
                document.getElementById('error-message').style.display = 'none'; // Hide error message if an answer is selected
            }

            questions[currentQuestionIndex].style.display = 'none';
            currentQuestionIndex += direction;

            if (currentQuestionIndex < 0) {
                currentQuestionIndex = 0;
            } else if (currentQuestionIndex >= questions.length) {
                currentQuestionIndex = questions.length - 1;
            }

            // Update the display of the current question
            questions[currentQuestionIndex].style.display = 'block';

            // Update button visibility and text
            updateButtons();
        }

        function updateButtons() {
            document.getElementById('prevBtn').style.display = currentQuestionIndex === 0 ? 'none' : 'block';
            document.getElementById('backBtn').style.display = currentQuestionIndex === 0 ? 'block' : 'none'; // Show/hide Back button
            // document.getElementById('prevBtn').style.display = currentQuestionIndex === 0 ? 'none' : 'block';
            
            if (currentQuestionIndex === questions.length - 1) {
                document.getElementById('finalNote').style.display = 'block'; // Show the final note about not being able to change answers
                document.getElementById('nextBtn').innerText = 'Done'; // Change button text to "Done"
                document.getElementById('submitBtn').style.display = 'none'; // Hide submit button initially
            } else {
                document.getElementById('finalNote').style.display = 'none'; // Hide the final note
                document.getElementById('nextBtn').innerText = 'Next'; // Reset button text to "Next"
                document.getElementById('submitBtn').style.display = 'none'; // Hide submit button
            }
        }

        function handleDone() {
            // Hide the Done and Previous buttons
            document.getElementById('nextBtn').style.display = 'none'; // Hide Done button
            document.getElementById('prevBtn').style.display = 'none'; // Hide Previous button
            document.getElementById('submitBtn').style.display = 'block'; // Show Submit button
        }

        function clearErrorMessage() {
            document.getElementById('error-message').style.display = 'none'; // Hide error message when an answer is selected
        }

        let timer;
        let duration = <?= $quiz['duration'] ?>; // Duration in minutes
        let remainingTime = duration * 60; // Convert minutes to seconds

        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;

            return String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(secs).padStart(2, '0');
        }

        function startTimer() {
            timer = setInterval(function() {
                if (remainingTime <= 0) {
                    clearInterval(timer);
                    document.getElementById("quizForm").submit();
                } else {
                    remainingTime--;
                    document.getElementById("timer").innerText = formatTime(remainingTime) + " remaining";
                }
            }, 1000);
        }

        window.onload = function() {
            startTimer();
            document.getElementById('prevBtn').style.display = 'none'; // Hide previous button initially
            document.getElementById('nextBtn').onclick = function() {
                if (currentQuestionIndex === questions.length - 1) {
                    handleDone(); // Call handleDone if on the last question
                } else {
                    changeQuestion(1); // Move to the next question
                }
            };
        };
    </script>
    
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