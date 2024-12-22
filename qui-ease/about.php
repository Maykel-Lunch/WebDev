<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Inquizitive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> <!-- Link to Google Fonts -->
    <style>
        body {
            font-family: 'Libre Baskerville', serif; 
            background-color:rgb(238, 251, 255); 
        }
        h1, h2 {
            color: #343a40;
        }
        .card {
            transition: transform 0.2s;
            padding: 10px;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-img-top {
            height: 150px; 
            width: 150px;
            object-fit: cover; 
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

    <div class="container mt-5" style="max-width: 700px;">
        <h1 class="text-center mb-4"><strong>About Inquizitive</strong></h1>
        <div class="card">
            <p class="text-center">Welcome to Inquizitive, your ultimate quiz-making companion! Whether you're an educator, a business professional, or a trivia enthusiast, Inquizitive is designed for you.</p>
        </div>

        <h2 class="text-center mt-5">Our Mission</h2>
        <div class="card">
            <p class="text-center">At Inquizitive, our mission is to empower users to create, share, and enjoy quizzes that make learning fun and interactive. We believe that knowledge should be accessible and engaging.</p>
        </div>

        <h2 class="text-center mt-5" >Our Story</h2>
        <div class="card">
            <div class="card-body">
                <p class="text-center">Founded in December 2024, Qui-ease started as a small project by a group of students and tech enthusiasts who wanted to make learning more interactive.</p>
            </div>
            
        </div>

        <h2 class="text-center mt-5">Features</h2>
        <ul class="list-group text mx-auto">
            <li class="list-group-item"><strong>Easy Quiz Creation:</strong> Design quizzes in just a few minutes</li>
            <li class="list-group-item"><strong>Engage with Community Quizzes: </strong> Discover and take part in quizzes crafted by fellow users</li>
        </ul>

        <h2 class="text-center mt-5">Meet the Team</h2>
        <div class="row text-center ">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="d-flex justify-content-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/7230/7230285.png" class="card-img-top" alt="Team Member 1">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Michael N. Lonceras</h4>
                        <p class="card-text">Developer</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="d-flex justify-content-center">
                    <img src="https://www.pngarts.com/files/5/User-Avatar-Download-Transparent-PNG-Image.png" class="card-img-top" alt="Team Member 2">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Gian Addy T. Maraño</h4>
                        <p class="card-text">Tester</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="d-flex justify-content-center">
                    <img src="https://www.pngarts.com/files/11/Avatar-PNG-Picture.png" class="card-img-top" alt="Team Member 3">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Christopher James Sayson</h4>
                        <p class="card-text">Developer</p>
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
    
    <footer class="bg-secondary text-center text-lg-start">
        <div class="text-center p-3">
            <p class="text-white">&copy; <?php echo date("Y"); ?> Inquizitive. All rights reserved.</p>
            <a href="about.php" class="text-white">About Us |</a> 
            <a href="contact.php" class="text-white">Contact |</a> 
            <a href="privacy.php" class="text-white">Privacy Policy</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>