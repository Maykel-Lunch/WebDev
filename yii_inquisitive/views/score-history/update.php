<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ScoreHistory $model */

$this->title = 'Update Score History: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Score Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="score-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
