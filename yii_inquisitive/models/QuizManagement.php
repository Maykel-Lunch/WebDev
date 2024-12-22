<?php

namespace app\models;

use Yii;
use yii\base\Model;

class QuizManagement extends Model
{
    public $quiz_title;
    public $quiz_topic;
    public $quiz_description;
    public $num_of_questions;
    public $quiz_duration;
    public $questions = [];

    public function rules()
    {
        return [
            [['quiz_title', 'quiz_topic', 'quiz_description', 'num_of_questions', 'quiz_duration'], 'required'],
            [['num_of_questions', 'quiz_duration'], 'integer'],
            [['quiz_title', 'quiz_topic'], 'string', 'max' => 255],
            [['quiz_description'], 'string'],
        ];
    }

    public function saveQuiz()
    {
        $db = Yii::$app->db;

        // Insert quiz into database
        $command = $db->createCommand()->insert('quizzes', [
            'title' => $this->quiz_title,
            'topic' => $this->quiz_topic,
            'description' => $this->quiz_description,
            'date' => date('Y-m-d H:i:s'),
            'duration' => $this->quiz_duration,
            'user_id' => Yii::$app->user->id, // Assuming user is logged in
        ]);
        $quiz_id = $command->execute();

        // Insert questions into database
        foreach ($this->questions as $question) {
            $db->createCommand()->insert('questions', [
                'quiz_id' => $quiz_id,
                'text' => $question['text'],
                'type' => $question['type'],
                'correct_answer' => $question['correct_answer'],
                'choices' => implode(',', $question['choices']),
            ])->execute();
        }

        return true;
    }
}