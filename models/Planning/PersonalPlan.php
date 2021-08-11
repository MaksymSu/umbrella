<?php

namespace frontend\models\Planning;

use frontend\models\ExecutorAssignment;
use frontend\models\NormJob;
use frontend\models\Resident;
use frontend\models\Themes;
use Yii;


class PersonalPlan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $executor_div_id;
    public $executor_sector_id;
    public $master_div_id;
    public $state;
    public $theme_id_search;

    public $statuses =[5=>'В процесі',1=>'Видано виконавцю', 2=>'Прийнято', 3=>'Виконано', 4=>'На доробку'];

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
            [['resident_id', 'theme_id', 'status', 'executor_div_id', 'executor_sector_id'], 'integer'],
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
            'theme_id' => 'Тема',
            'content' => 'Зміст роботи',
            'started_at' => 'Дата початку',
            'finished_at' => 'Дата закінчення',
            'created_at' => 'Created At',
            'status' => 'Status',
            'desc' => 'Коментар',
            'labor' => 'Трудомісткість',
            'started_at_fact' => 'Started At Fact',
            'finished_at_fact' => 'Finished At Fact',
            'executor_div_id' => 'Відділ-виконавець',
            'executor_sector_id' => 'Сектор-виконавець',
            'master_div_id' => 'Відповідальний відділ',
            'state' => ''
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

    public function setResident(){
        $this->resident_id = Resident::findOne(['user_id' => Yii::$app->user->id])->id;
    }

    public static function getMyDiv(){
        return  \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->div;
    }

    public function getExecDiv($id){
        return ExecDivAssignment::findOne(['job_id'=>$id]);
    }

    public function isInWork(){
        if(ExecutorAssignment::findOne(['parent_job_id'=>$this->id]))return true;
        return false;
    }
}
