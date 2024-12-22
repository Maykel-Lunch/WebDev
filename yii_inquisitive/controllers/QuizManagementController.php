<?php

namespace app\controllers;

use Yii;
use app\models\QuizManagement;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class QuizManagementController extends Controller
{
    public function actionIndex()
    {
        $model = new QuizManagement();
    
        if ($model->load(Yii::$app->request->post())) {
            $model->questions = Yii::$app->request->post('questions', []);
            if ($model->validate() && $model->saveQuiz()) {
                Yii::$app->session->setFlash('success', 'Quiz created successfully!');
                return $this->redirect(['index']);
            }
        }
    
        return $this->render('index', [
            'model' => $model,
        ]);
    }
}