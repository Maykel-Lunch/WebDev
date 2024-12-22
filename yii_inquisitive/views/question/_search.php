<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\QuestionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'quiz_id') ?>

    <?= $form->field($model, 'text') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'correct_answer') ?>

    <?php // echo $form->field($model, 'choices') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
