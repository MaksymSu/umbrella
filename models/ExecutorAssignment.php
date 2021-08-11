<?php

namespace frontend\models;

use frontend\models\Planning\ExecDivAssignment;
use Yii;

/**
 * This is the model class for table "executor_assignment".
 *
 * @property int $id
 * @property int $job_id
 * @property int $resident_id
 * @property int $persent
 * @property string $created_at
 * @property int $sorcerer_id
 * @property string $desc
 *
 * @property PersonalPlan $job
 * @property Resident $resident
 */
class ExecutorAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'executor_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'resident_id', 'persent', 'sorcerer_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['desc'], 'string'],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalPlan::className(), 'targetAttribute' => ['job_id' => 'id']],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'resident_id' => 'Resident ID',
            'persent' => 'Persent',
            'created_at' => 'Created At',
            'sorcerer_id' => 'Sorcerer ID',
            'desc' => 'Desc',
            'status' => 'Стан',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(PersonalPlan::className(), ['id' => 'job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'resident_id']);
    }

    public function getParentJob()
    {
        return $this->hasOne(PersonalPlan::className(), ['id' => 'parent_job_id']);
    }
}
