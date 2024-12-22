<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "cries"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize a variable to track registration status
$registration_successful = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($user_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (user_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_name, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            $registration_successful = true; // Set the flag to true on successful registration
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register : Inquizitive</title>
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
            background-color: rgb(238, 251, 255);
        }
        footer {
            margin-top: auto; /* Push footer to the bottom */
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
        <h2 class="text-center">Register</h2>

        <?php if ($registration_successful): ?>
            <div class='alert alert-success'>Registration successful! You can now <a href='login.php'>login</a>.</div>
        <?php elseif (isset($error_message)): ?>
            <div class='alert alert-danger'><?php echo htmlspecialchars($error_message); ?></div>
        <?php else: ?>
            <!-- Card for the registration form -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password:</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Register</button>
                            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a>.</p>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <footer class="bg-secondary text-center text-lg-start">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>