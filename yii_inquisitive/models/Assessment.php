<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "assessments".
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
 * @property User $user
 * @property Question[] $questions
 */
class Assessment extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'quizzes'; // Assuming the table name remains the same
    }

    public function rules()
    {
        return [
            [['title', 'topic', 'date', 'duration', 'user_id'], 'required'],
            [['description'], 'string'],
            [['duration', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'topic'], 'string', 'max' => 255],
        ];
    }

    public function getUser ()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['quiz_id' => 'id']);
    }
}