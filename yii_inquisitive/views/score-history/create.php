<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ScoreHistory $model */

$this->title = 'Create Score History';
$this->params['breadcrumbs'][] = ['label' => 'Score Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="score-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
