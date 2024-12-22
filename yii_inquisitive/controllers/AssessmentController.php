<?php


namespace app\controllers;

use Yii;
use app\models\Assessment; // Use the new model name
use app\models\Question;
use app\models\ScoreHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AssessmentController extends Controller
{
    public function actionIndex()
    {
        $assessments = Assessment::find()->all();
        return $this->render('index', ['assessments' => $assessments]);
    }

    public function actionTake($id)
    {
        $assessment = $this->findAssessment($id);
        $questions = $assessment->questions;

        if (Yii::$app->request->isPost) {
            $answers = Yii::$app->request->post('answers');
            $score = 0;

            foreach ($questions as $question) {
                if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_answer) {
                    $score++;
                }
            }

            // Save score to history
            $scoreHistory = new ScoreHistory();
            $scoreHistory->user_id = Yii::$app->user->id;
            $scoreHistory->quiz_id = $assessment->id; // Still using quiz_id for the score history
            $scoreHistory->title = $assessment->title;
            $scoreHistory->time_taken = date('Y-m-d H:i:s');
            $scoreHistory->score = $score;
            $scoreHistory->save();

            return $this->render('result', ['assessment' => $assessment, 'score' => $score, 'total' => count($questions)]);
        }

        return $this->render('take', ['assessment' => $assessment, 'questions' => $questions]);
    }

    protected function findAssessment($id)
    {
        if (($assessment = Assessment::findOne($id)) !== null) {
            return $assessment;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}