<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $text
 * @property string $type
 * @property string $correct_answer
 * @property string $choices
 *
 * @property Quizzes $quiz
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'text', 'type', 'correct_answer', 'choices'], 'required'],
            [['quiz_id'], 'integer'],
            [['text', 'type', 'choices'], 'string'],
            [['correct_answer'], 'string', 'max' => 255],
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
            'quiz_id' => 'Quiz ID',
            'text' => 'Text',
            'type' => 'Type',
            'correct_answer' => 'Correct Answer',
            'choices' => 'Choices',
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
}
