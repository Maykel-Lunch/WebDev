<?php
use yii\helpers\Html;

$this->title = 'Assessment Results';
?>
<div class="assessment-result">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="card mt-3">
        <div class="card-body">
            <!-- Canvas for Pie Chart -->
            <div class="m-4">
                <canvas id="resultChart" width="10" height="10"></canvas>
            </div>
            <h5 class="card-title">Assessment Title: <?= Html::encode($assessment->title) ?></h5>
            <p class="card-text">Your Score: <strong><?= $score ?></strong> out of <strong><?= $total ?></strong></p>
            <?= Html::a('Back to Assessments', ['assessment/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data for the pie chart
    const score = <?= $score ?>;
    const total = <?= $total ?>;
    const incorrect = total - score;

    const ctx = document.getElementById('resultChart').getContext('2d');
    const resultChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Correct Answers', 'Incorrect Answers'],
            datasets: [{
                label: 'Assessment Results',
                data: [score, incorrect],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Correct Answers color
                    'rgba(255, 99, 132, 0.6)'  // Incorrect Answers color
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Assessment Results'
                }
            }
        }
    });
</script>