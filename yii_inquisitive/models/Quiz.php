<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quizzes".
 *
 * @property int $id
 * @property string $title
 * @property string $topic
 * @property string|null $description
 * @property string $date
 * @property int $duration
 * @property string $created_at
 * @property int $user_id
 *
 * @property Questions[] $questions
 * @property ScoreHistory[] $scoreHistories
 * @property Users $user
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quizzes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'topic', 'date', 'duration', 'user_id'], 'required'],
            [['description'], 'string'],
            [['date', 'created_at'], 'safe'],
            [['duration', 'user_id'], 'integer'],
            [['title', 'topic'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'topic' => 'Topic',
            'description' => 'Description',
            'date' => 'Date',
            'duration' => 'Duration',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Questions::class, ['quiz_id' => 'id']);
    }

    /**
     * Gets query for [[ScoreHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScoreHistories()
    {
        return $this->hasMany(ScoreHistory::class, ['quiz_id' => 'id']);
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
