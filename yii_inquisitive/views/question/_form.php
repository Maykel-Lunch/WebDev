<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Question $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quiz_id')->textInput() ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'truefalse' => 'Truefalse', 'multiplechoice' => 'Multiplechoice', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'correct_answer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'choices')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
