<?php
session_start();
// Database connection
$host = 'localhost'; 
$db = 'cries';
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User  ';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id']; // Now this will hold the actual user ID of the logged-in user

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get quiz details
    $title = $_POST['quiz_title'];
    $topic = $_POST['quiz_topic'];
    $description = $_POST['quiz_description'];
    $num_of_questions = $_POST['num_of_questions'];
    $duration = $_POST['quiz_duration'];

    // Insert quiz into database
    $stmt = $conn->prepare("INSERT INTO quizzes (title, topic, description, date, duration, user_id) VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("sssis", $title, $topic, $description, $duration, $user_id);
    $stmt->execute();
    $quiz_id = $stmt->insert_id; 
    $stmt->close();

    // Insert questions into database
    for ($i = 0; $i < $num_of_questions; $i++) {
        $question_text = $_POST["question_text_$i"];
        $question_type = $_POST["question_type_$i"];
        $correct_answer = $_POST["correct_answer_$i"];
        $choices = isset($_POST["choices_$i"]) ? implode(',', $_POST["choices_$i"]) : '';

        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, text, type, correct_answer, choices) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $quiz_id, $question_text, $question_type, $correct_answer, $choices);
        $stmt->execute();
        $stmt->close();
    }

    // echo "<div class='alert alert-success'>Quiz created successfully!</div>";
    // Set success message in session and redirect
    $_SESSION['success_message'] = "Quiz created successfully!";
    header("Location: read.php?id=" . $quiz_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz : Inquizitive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .question-title {
            font-size: 1.2rem; /* Adjust the font size as needed */
            background-color:rgb(247, 231, 193); /* Light blue background for highlighting */
            padding: 10px; /* Add some padding */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 10px; /* Space below each question */
        }
        h5{
            font-size: 1rem;
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
        <h2 class="mb-4 text-center">Create a Quiz</h2>
        <form method="POST" class="bg-white p-4 rounded shadow">
            <div class="mb-3">
                <label for="quiz_title" class="form-label">Quiz Title:</label>
                <input type="text" class="form-control" name="quiz_title" required>
            </div>

            <div class="mb-3">
                <label for="quiz_topic" class="form-label">Quiz Topic:</label>
                <input type="text" class="form-control" name="quiz_topic" required>
            </div>

            <div class="mb-3">
                <label for="quiz_description" class="form-label">Quiz Description:</label>
                <textarea class="form-control" name="quiz_description" required></textarea>
            </div>

            <div class="mb-3">
                <label for="num_of_questions" class="form-label">Number of Questions (max 30):</label>
                <input type="number" id="num_of_questions" class="form-control" name="num_of_questions" max="30" required oninput="generateQuestions(), validateNumberOfQuestions()">
                <div id="error_message" class="text-danger" style="display:none;">Please enter a number between 1 and 30.</div>
            </div>

            <div class="mb-3">
                <label for="quiz_duration" class="form-label">Quiz Duration (in minutes):</label>
                <input type="number" class="form-control" name="quiz_duration" required>
            </div>

            <div id="questions_container"></div>

            <button type="submit" class="btn btn-primary">Create Quiz</button>
        </form>
    </div>
    
    <footer class="bg-secondary text-center text-lg-start">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>
    
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

    <script>
        function generateQuestions() {
            const numQuestions = parseInt(document.getElementById('num_of_questions').value, 10);
            const questionsContainer = document.getElementById('questions_container');
            questionsContainer.innerHTML = '';

            // Validation: Check if numQuestions is between 1 and 30
            if (numQuestions >= 1 && numQuestions <= 30) {
                for (let i = 0; i < numQuestions; i++) {
                    const questionDiv = document.createElement('div');
                    questionDiv.classList.add('mb-3');
                    questionDiv.innerHTML = `
                        <h4 class='question-title'>Question ${i + 1}</h4>
                        <input type="text" class="form-control mb-2" name="question_text_${i}" placeholder="Question Text" required>
                        <select class="form-select mb-2" name="question_type_${i}" onchange="updateOptions(${i})">
                            <option value="truefalse" selected>True/False</option>
                            <option value="multiplechoice">Multiple Choice</option>
                        </select>
                        <div id="options_${i}" style="display:none;">
                            <h5>Choices:</h5>
                            <input type="text" class="form-control mb-2" name="choices_${i}[]" placeholder="Choice 1">
                            <input type="text" class="form-control mb-2" name="choices_${i}[]" placeholder="Choice 2">
                            <input type="text" class="form-control mb-2" name="choices_${i}[]" placeholder="Choice 3">
                            <input type="text" class="form-control mb-2" name="choices_${i}[]" placeholder="Choice 4">
                        </div>
                        <div id="correct_answer_container_${i}" style="display:block;">
                            <h5>Correct Answer:</h5>
                            <select class="form-select mb-2" name="correct_answer_${i}">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                        <div id="correct_answer_mc_container_${i}" style="display:none;">
                            <h5>Correct Answer:</h5>
                            <select class="form-select mb-2" name="correct_answer_mc_${i}">
                                <option value="0">Choice 1</option>
                                <option value="1">Choice 2</option>
                                <option value="2">Choice 3</option>
                                <option value="3">Choice 4</option>
                            </select>
                        </div>
                    `;
                    questionsContainer.appendChild(questionDiv);
                }
            }
        }

        function updateOptions(index) {
            const questionType = document.querySelector(`select[name="question_type_${index}"]`).value;
            const optionsDiv = document.getElementById(`options_${index}`);
            const correctAnswerContainer = document.getElementById(`correct_answer_container_${index}`);
            const correctAnswerMCContainer = document.getElementById(`correct_answer_mc_container_${index}`);

            if (questionType === 'multiplechoice') {
                optionsDiv.style.display = 'block';
                correctAnswerContainer.style.display = 'none';
                correctAnswerMCContainer.style.display = 'block';
            } else {
                optionsDiv.style.display = 'none';
                correctAnswerContainer.style.display = 'block';
                correctAnswerMCContainer.style.display = 'none';
            }
        }

        function validateNumberOfQuestions() {
            const numQuestionsInput = document.getElementById('num_of_questions');
            const errorMessage = document.getElementById('error_message');
            const numQuestions = parseInt(numQuestionsInput.value);

            if (numQuestions < 1 || numQuestions > 30) {
                errorMessage.style.display = 'block';
                numQuestionsInput.setCustomValidity(''); // Clear any previous custom validity
            } else {
                errorMessage.style.display = 'none';
                numQuestionsInput.setCustomValidity(''); // Clear any previous custom validity
            }
        }
    </script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>