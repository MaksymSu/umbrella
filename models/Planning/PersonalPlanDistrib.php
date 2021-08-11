<?php

namespace frontend\models\Planning;

use Yii;

/**
 * This is the model class for table "personal_plan".
 *
 * @property int $id
 * @property int $resident_id
 * @property int $theme_id
 * @property string $content
 * @property string $started_at
 * @property string $finished_at
 * @property string $created_at
 * @property int $status
 * @property string $desc
 * @property double $labor
 * @property string $started_at_fact
 * @property string $finished_at_fact
 *
 * @property ExecutorAssignment[] $executorAssignments
 * @property NormJob[] $normJobs
 * @property Resident $resident
 * @property Themes $theme
 */
class PersonalPlanDistrib extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personal_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resident_id', 'theme_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['started_at', 'finished_at', 'created_at', 'started_at_fact', 'finished_at_fact'], 'safe'],
            [['labor'], 'number'],
            [['desc'], 'string', 'max' => 2550],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
            [['theme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Themes::className(), 'targetAttribute' => ['theme_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resident_id' => 'Resident ID',
            'theme_id' => 'Theme ID',
            'content' => 'Content',
            'started_at' => 'Started At',
            'finished_at' => 'Finished At',
            'created_at' => 'Created At',
            'status' => 'Status',
            'desc' => 'Desc',
            'labor' => 'Labor',
            'started_at_fact' => 'Started At Fact',
            'finished_at_fact' => 'Finished At Fact',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorAssignments()
    {
        return $this->hasMany(ExecutorAssignment::className(), ['job_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormJobs()
    {
        return $this->hasMany(NormJob::className(), ['job_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'resident_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Themes::className(), ['id' => 'theme_id']);
    }
}
