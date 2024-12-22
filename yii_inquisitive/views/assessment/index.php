<?php
use yii\helpers\Html;

$this->title = 'Assessments';
?>
<div class="assessment-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <ul class="list-group">
        <?php foreach ($assessments as $assessment): ?>
            <li class="list-group-item">
                <?= Html::encode($assessment->title) ?>
                <?= Html::a('Take Assessment', ['assessment/take', 'id' => $assessment->id], ['class' => 'btn btn-primary float-end']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>