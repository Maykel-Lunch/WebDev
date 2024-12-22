<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = 'localhost'; 
$db = 'cries';
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch quiz details if ID is provided
if (isset($_GET['id'])) {
    $quiz_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT title, topic, description, duration FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quiz = $result->fetch_assoc();
    $stmt->close();

    // Fetch questions for the quiz
    $stmt = $conn->prepare("SELECT id, text, type, correct_answer, choices FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $questions_result = $stmt->get_result();
    $questions = [];
    while ($question = $questions_result->fetch_assoc()) {
        $questions[] = $question;
    }
    $stmt->close();
} else {
    die("Quiz ID not provided.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get quiz details
    $title = $_POST['quiz_title'];
    $topic = $_POST['quiz_topic'];
    $description = $_POST['quiz_description'];
    $duration = $_POST['quiz_duration'];

    // Update quiz in database
    $stmt = $conn->prepare("UPDATE quizzes SET title = ?, topic = ?, description = ?, duration = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $title, $topic, $description, $duration, $quiz_id);
    $stmt->execute();
    $stmt->close();

    // Update questions
    foreach ($_POST['question_id'] as $index => $question_id) {
        $question_text = $_POST["question_text_$index"];
        $question_type = $_POST["question_type_$index"];
        $correct_answer = $_POST["correct_answer_$index"];
        $choices = isset($_POST["choices_$index"]) ? implode(',', $_POST["choices_$index"]) : '';

        $stmt = $conn->prepare("UPDATE questions SET text = ?, type = ?, correct_answer = ?, choices = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $question_text, $question_type, $correct_answer, $choices, $question_id);
        $stmt->execute();
        $stmt->close();
    }


    // Set success message in session and redirect
    $_SESSION['success_message'] = "Quiz updated successfully!";
    header("Location: read.php?id=" . $quiz_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Libre Baskerville', serif; 
            background-color:rgb(238, 251, 255);
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
        <h2 class="mb-4 text-center">Edit Quiz</h2>
        <form method="POST" class="bg-white p-4 rounded shadow">
            
            <div class="mb-3">
                <label for="quiz_title" class="form-label">Quiz Title:</label>
                <input type="text" class="form-control" name="quiz_title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="quiz_topic" class="form-label">Quiz Topic:</label>
                <input type="text" class="form-control" name="quiz_topic" value="<?php echo htmlspecialchars($quiz['topic']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="quiz_description" class="form-label">Quiz Description:</label>
                <textarea class="form-control" name="quiz_description" required><?php echo htmlspecialchars($quiz['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="quiz_duration" class="form-label">Quiz Duration (in minutes):</label>
                <input type="number" class="form-control" name="quiz_duration" value="<?php echo htmlspecialchars($quiz['duration']); ?>" required>
            </div>

            <div id="questions_container"></div>

            <div class="d-flex justify-content-between">
                <a href="read.php" class="btn btn-secondary m-2">Back</a>
                <button type="submit" class="btn btn-primary m-2">Update Quiz</button>
            </div>
        </form>
    </div>

    <footer class="bg-secondary text-center text-lg-start mt-5">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>

    <script>
        function generateQuestions() {
            const questionsContainer = document.getElementById('questions_container');
            questionsContainer.innerHTML = '';

            questions.forEach((question, index) => {
                const questionDiv = document.createElement('div');
                questionDiv.classList.add('mb-3');
                questionDiv.innerHTML = `
                    <h4 class='question-title'>Question ${index + 1}</h4>
                    <input type="hidden" name="question_id[]" value="${question.id}">
                    <input type="text" class="form-control mb-2" name="question_text_${index}" value="${question.text}" placeholder="Question Text" required>
                    <select class="form-select mb-2" name="question_type_${index}" onchange="updateOptions(${index})">
                        <option value="truefalse" ${question.type === 'truefalse' ? 'selected' : ''}>True/False</option>
                        <option value="multiplechoice" ${question.type === 'multiplechoice' ? 'selected' : ''}>Multiple Choice</ option>
                    </select>
                    <div id="options_${index}" style="${question.type === 'multiplechoice' ? 'display:block;' : 'display:none;'}">
                        <h5>Choices:</h5>
                        ${question.choices.split(',').map((choice, choiceIndex) => `
                            <input type="text" class="form-control mb-2" name="choices_${index}[]" value="${choice}" placeholder="Choice ${choiceIndex + 1}">
                        `).join('')}
                    </div>
                    <h5> Correct Answer </h5>
                    <select class="form-select mb-2" name="correct_answer_${index}">
                        <option value="true" ${question.correct_answer === 'true' ? 'selected' : ''}>True</option>
                        <option value="false" ${question.correct_answer === 'false' ? 'selected' : ''}>False</option>
                        ${question.type === 'multiplechoice' ? `
                            ${question.choices.split(',').map((choice, choiceIndex) => `
                                <option value="${choice}" ${question.correct_answer === choice ? 'selected' : ''}>Choice ${choiceIndex + 1}</option>
                            `).join('')}
                        ` : ''}
                    </select>
                `;
                questionsContainer.appendChild(questionDiv);
            });
        }

        function updateOptions(index) {
            const type = document.querySelector(`select[name="question_type_${index}"]`).value;
            const optionsDiv = document.getElementById(`options_${index}`);
            optionsDiv.style.display = type === 'multiplechoice' ? 'block' : 'none';
        }
    </script>

    <script>
        const questions = <?php echo json_encode($questions); ?>;
        generateQuestions();
    </script>
</body>
</html>