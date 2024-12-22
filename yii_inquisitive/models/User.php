<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property string $user_name
 * @property string $email
 * @property string $password
 * @property string $created_at
 *
 * @property Quizzes[] $quizzes
 * @property ScoreHistory[] $scoreHistories
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_name', 'email', 'password'], 'required'],
            [['created_at'], 'safe'],
            [['user_name', 'email', 'password'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'email' => 'Email',
            'password' => 'Password',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Quizzes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuizzes()
    {
        return $this->hasMany(Quizzes::class, ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[ScoreHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScoreHistories()
    {
        return $this->hasMany(ScoreHistory::class, ['user_id' => 'user_id']);
    }

    
}

