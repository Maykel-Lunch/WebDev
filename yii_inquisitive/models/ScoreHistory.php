<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "score_history".
 *
 * @property int $id
 * @property int $user_id
 * @property int $quiz_id
 * @property string $title
 * @property string $time_taken
 * @property int $score
 *
 * @property Quizzes $quiz
 * @property Users $user
 */
class ScoreHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'score_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'quiz_id', 'title', 'time_taken', 'score'], 'required'],
            [['user_id', 'quiz_id', 'score'], 'integer'],
            [['time_taken'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::class, 'targetAttribute' => ['quiz_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'quiz_id' => 'Quiz ID',
            'title' => 'Title',
            'time_taken' => 'Time Taken',
            'score' => 'Score',
        ];
    }

    /**
     * Gets query for [[Quiz]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quizzes::class, ['id' => 'quiz_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }
}
