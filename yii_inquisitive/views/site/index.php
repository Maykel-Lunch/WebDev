<?php

/** @var yii\web\View $this */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\base\DynamicModel;

$this->title = 'Qui-ease : Create Quizzes';

// Check if the user is logged in as admin
$isAdmin = Yii::$app->session->get('isAdmin', false);

// Create a DynamicModel for the login form
$model = new DynamicModel(['password']);
$model->addRule(['password'], 'required');

// Handle login/logout
if (Yii::$app->request->isPost) {
    if (isset($_POST['login'])) {
        // Load the password into the model
        $model->password = $_POST['DynamicModel']['password'];
        
        // Check if the password is set and correct
        if ($model->password === 'admin123') {
            Yii::$app->session->set('isAdmin', true);
            $isAdmin = true;
        }
    } elseif (isset($_POST['logout'])) {
        // Logout the admin
        Yii::$app->session->remove('isAdmin');
        $isAdmin = false;
    }
}
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Welcome to Qui-ease!</h1>

        <p class="lead">You have successfully launched your Qui-ease quiz app, ready to challenge your knowledge and entertain your mind!</p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['assessment/index']) ?>">Take a quiz</a></p>
    </div>

    <div class="body-content">

        <?php if (!$isAdmin): // Only show these cards if the admin is not logged in ?>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="card bg-light text-center fixed-size-card">
                        <div class="card-body">
                            <h2 class="card-title">Create Quiz</h2>
                            <p class="card-text">Create and manage quizzes in the application.</p>
                            <p><a class="btn btn-outline-secondary" href="<?= Url::to(['quiz-management/index']) ?>">Create Quiz Now&raquo;</a></p>
                        </div>
                    </div>     
                </div>  
                <div class="col-lg-6 mb-3">
                    <div class="card bg-light text-center fixed-size-card">
                        <div class="card-body">
                            <h2 class="card-title">Manage Database</h2>
                            <p class="card-text">View and manage the application's database records.</p>
                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#adminLoginModal">Admin Login&raquo;</button>
                        </div>
                    </div>     
                </div> 
            </div>
        <?php else: // Show admin management cards if logged in ?>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h2 class="card-title">Users</h2>
                            <p class="card-text">Manage users in the application.</p>
                            <p><a class="btn btn-outline-secondary" href="<?= Url::to(['user/index']) ?>">View Users &raquo;</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h2 class="card-title">Quizzes</h2>
                            <p class="card-text">Manage quizzes available in the application.</p>
                            <p><a class="btn btn-outline-secondary" href="<?= Url::to(['quiz/index']) ?>">View Quizzes &raquo;</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h2 class="card-title">Questions</h2>
                            <p class="card-text">Manage questions associated with quizzes.</ <p><a class="btn btn-outline-secondary" href="<?= Url::to(['question/index']) ?>">View Questions &raquo;</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h2 class="card-title">Score History</h2>
                            <p class="card-text">View the score history of users.</p>
                            <p><a class="btn btn-outline-secondary" href="<?= Url::to(['score-history/index']) ?>">View Score History &raquo;</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php $form = ActiveForm::begin(['method' => 'post']); ?>
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>
</div>

<!-- Admin Login Modal -->
<div class="modal fade" id="adminLoginModal" tabindex="-1" role="dialog" aria-labelledby="adminLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header d-flex justify-content-between">
            <h5 class="modal-title mr-auto" id="adminLoginModalLabel">Admin Login</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['method' => 'post']); ?>
                    <?= $form->field($model, 'password')->passwordInput()->label('Password') ?>
                    <div class="form-group">
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Add jQuery and Bootstrap JS dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>