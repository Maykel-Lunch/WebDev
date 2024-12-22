<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About Qui-ease';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Libre Baskerville', serif; 
            background-color: #f8f9fa;
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
        .lead {
            font-size: 1.25rem;
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

    <div class="container mt-5" style="max-width: 700px;">
        <h1 class="text-center mb-4"><strong><?= Html::encode($this->title) ?></strong></h1>
        <div class="card">
            <p class="text-center">Welcome to Qui-ease, your ultimate quiz-making companion! Whether you're an educator, a business professional, or a trivia enthusiast, Qui-ease is designed for you.</p>
        </div>

        <h2 class="text-center mt-5">Our Mission</h2>
        <div class="card">
            <p class="text-center">At Qui-ease, our mission is to empower users to create, share, and enjoy quizzes that make learning fun and interactive. We believe that knowledge should be accessible and engaging.</p>
        </div>

        <h2 class="text-center mt-5">Our Story</h2>
        <div class="card">
            <div class="card-body">
                <p class="text-center">Founded in December 2024, Qui-ease started as a small project by a group of students and tech enthusiasts who wanted to make learning more interactive.</p>
            </div>
        </div>

        <h2 class="text-center mt-5">Features</h2>
        <ul class="list-group text mx-auto">
            <li class="list-group-item"><strong>Easy Quiz Creation:</strong> Design quizzes in just a few minutes</li>
            <li class="list-group-item"><strong >Engage with Community Quizzes:</strong> Discover and take part in quizzes crafted by fellow users</li>
        </ul>

        <h2 class="text-center mt-5">Meet the Team</h2>
        <div class="row text-center">
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
                    <a href="<?= \yii\helpers\Url::to(['site/logout']) ?>" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>