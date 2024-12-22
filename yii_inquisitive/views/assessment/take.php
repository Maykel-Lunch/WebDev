<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Html::encode($assessment->title);
$duration = $assessment->duration * 60; // Convert minutes to seconds
?>
<div class="assessment-take">
    <h1><?= $this->title ?></h1>
    <div id="timer" class="alert alert-info text-center"><?= $assessment->duration ?> minutes remaining</div>
    <?php $form = ActiveForm::begin(['id' => 'assessment-form']); ?>
        <?php $questionNumber = 1; // Initialize question number ?>
        <?php foreach ($questions as $question): ?>
            <div class="mb-4">
                <p><?= Html::encode("{$questionNumber}. " . $question->text) ?></p> <!-- Display question number -->
                <?php if ($question->type == 'multiplechoice'): ?>
                    <?php $choices = explode(',', $question->choices); ?>
                    <?php foreach ($choices as $choice): ?>
                        <div class="form-check">
                            <?= Html::radio("answers[{$question->id}]", false, ['value' => Html::encode($choice), 'class' => 'form-check-input', 'id' => "choice_{$question->id}_" . Html::encode($choice)]) ?>
                            <?= Html::label(Html::encode($choice), "choice_{$question->id}_" . Html::encode($choice), ['class' => 'form-check-label']) ?>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($question->type == 'truefalse'): ?>
                    <div class="form-check">
                        <?= Html::radio("answers[{$question->id}]", false, ['value' => 'true', 'class' => 'form-check-input', 'id' => "true_{$question->id}"]) ?>
                        <?= Html::label('True', "true_{$question->id}", ['class' => 'form-check-label']) ?>
                    </div>
                    <div class="form-check">
                        <?= Html::radio("answers[{$question->id}]", false, ['value' => 'false', 'class' => 'form-check-input', 'id' => "false_{$question->id}"]) ?>
                        <?= Html::label('False', "false_{$question->id}", ['class' => 'form-check-label']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php $questionNumber++; // Increment question number ?>
        <?php endforeach; ?>
        <div class="form-group">
            <?= Html::submitButton('Submit Assessment', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    let duration = <?= $duration ?>; // Duration in seconds
    const timerElement = document.getElementById('timer');

    const countdown = setInterval(() => {
        const minutes = Math.floor(duration / 60);
        const seconds = duration % 60;

        timerElement.textContent = `${minutes} minutes ${seconds < 10 ? '0' : ''}${seconds} seconds remaining`;

        if (duration <= 0) {
            clearInterval(countdown);
            document.getElementById('assessment-form').submit(); // Automatically submit the form when time is up
        }

        duration--;
    }, 1000);
</script>