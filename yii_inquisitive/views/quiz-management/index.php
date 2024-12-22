<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Quiz';
?>

<div class="quiz-management-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-success" style="display: <?= Yii::$app->session->hasFlash('success') ? 'block' : 'none' ?>;">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quiz_title')->textInput()->label('Quiz Title') ?>
    <?= $form->field($model, 'quiz_topic')->textInput()->label('Quiz Topic') ?>
    <?= $form->field($model, 'quiz_description')->textarea()->label('Quiz Description') ?>
    
    <!-- Add oninput event to trigger question generation -->
    <?= $form->field($model, 'num_of_questions')->input('number', ['min' => 1, 'max' => 30, 'oninput' => 'validateAndGenerateQuestions()'])->label('Number of Questions (max 30)') ?>
    
    <?= $form->field($model, 'quiz_duration')->input('number')->label('Quiz Duration (in minutes)') ?>

    <div id="questions_container"></div>
    <div id="error_message" style="color: red; display: none;">Please enter a valid number of questions (1-30).</div>
    
    <button type="submit" class="btn btn-primary">Create Quiz</button>

    <?php ActiveForm::end(); ?>
</div>

<script>
    function validateAndGenerateQuestions() {
        const numQuestions = document.getElementById('quizmanagement-num_of_questions').value;
        const errorMessage = document.getElementById('error_message');
        const questionsContainer = document.getElementById('questions_container');
        
        // Validate input
        if (numQuestions < 1 || numQuestions > 30) {
            errorMessage.style.display = 'block';
            questionsContainer.innerHTML = ''; // Clear questions if input is invalid
            return;
        } else {
            errorMessage.style.display = 'none';
        }

        questionsContainer.innerHTML = '';

        for (let i = 0; i < numQuestions; i++) {
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('mb-3');
            questionDiv.innerHTML = `
                <h4>Question ${i + 1}</h4>
                <input type="text" class="form-control mb-2" name="questions[${i}][text]" placeholder="Question Text" required>
                <select class="form-select mb-2" name="questions[${i}][type]" onchange="updateOptions(${i})">
                    <option value="truefalse">True/False</option>
                    <option value="multiplechoice">Multiple Choice</option>
                </select>
                <div id="options_${i}" style="display:none;">
                    <h5>Choices:</h5>
                    <input type="text" class="form-control mb-2" name="questions[${i}][choices][]" placeholder="Choice 1">
                    <input type="text" class="form-control mb-2" name="questions[${i}][choices][]" placeholder="Choice 2">
                    <input type="text" class="form-control mb-2" name="questions[${i}][choices][]" placeholder="Choice 3">
                    <input type="text" class="form-control mb-2" name="questions[${i}][choices][]" placeholder="Choice 4">
                </div>
                <select class="form-select mb-2" name="questions[${i}][correct_answer]">
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            `;
            questionsContainer.appendChild(questionDiv);
        }
    }

    function updateOptions(index) {
        const type = document.querySelector(`select[name="questions[${index}][type]"]`).value;
        const optionsDiv = document.getElementById(`options_${index}`);
        optionsDiv.style.display = type === 'multiplechoice' ? 'block' : 'none';

        const correctAnswerSelect = document.querySelector(`select[name="questions[${index}][correct_answer]"]`);
        correctAnswerSelect.innerHTML = '';

        if (type === 'truefalse') {
            correctAnswerSelect.innerHTML = `
                <option value="true">True</option>
                <option value="false">False</option>
            `;
        } else {
            for (let i = 1; i <= 4; i++) {
                correctAnswerSelect.innerHTML += `<option value="Choice ${i}">Choice ${i}</option>`;
            }
        }
    }
</script>