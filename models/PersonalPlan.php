<?php

namespace frontend\models;

use Yii;
use yii\db\Query;

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
 *
 * @property Resident $resident
 * @property Themes $theme
 */
class PersonalPlan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $theme_content;
    public $theme_num;
    public $executor;
    public $total;

    public $div;
    public $sector;
    public $m;

    public $norm;

    public $statuses =[5=>'В процесі',1=>'Видано виконавцю', 2=>'Прийнято', 3=>'Виконано', 4=>'На доробку'];
   // public $colors = [3=>'#']

    public $resident_types = ['Внутрішній', 'Зовнішній'];
    public $resident_type;


    public $idid;
    public $percent;

    public $nodes;
    public $attachment_1;
    public $cur_nodes;
    public $cur_system;

    public $norm_percent;
    public $norm_percent_labor;




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

            [['resident_id', 'theme_id', 'status', 'executor','percent'], 'integer'],
            [['content', 'theme_content', 'desc', 'total', 'nodes','norm_percent_labor'], 'string'],

            [['nodes'], 'required',
                'when' => function ($model) {if (Yii::$app->request->get('r') == 'personal-plan/update'
                && $model->theme->number != '700'
                )return true;
                return false;},
                'message' => 'Виберіть елемент(и) дерева, по якому(яких) ведеться ця робота'],

            [['content'], 'required', 'message' => 'Заповніть зміст роботи'],
            [['theme_id'], 'required', 'message' => 'Виберіть тему'],
            [['idid'], 'integer', 'message' => 'Виберіть роботу'],
           // [['theme_id'], 'required', 'message' => 'Виберіть тему або пункт "без теми"'],
        //    [['executor'], 'required', 'message' => 'Виберіть виконавця'],

            [['started_at', 'finished_at', 'created_at', 'started_at_fact','finished_at_fact'], 'safe'],
            [['theme_num'], 'string', 'max' => 255],
            [['labor'], 'double'],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
            [['theme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Themes::className(), 'targetAttribute' => ['theme_id' => 'id']],
           // ['executor', 'required','message' =>'Потрібно вибрати'],
          //  ['theme_id', 'required','message' =>'Потрібно вибрати'],
            [['started_at', 'finished_at'], 'validateDate'],
            [['started_at_fact', 'finished_at_fact', 'status'], 'validateDateFact'],

           // [['started_at'], 'required', 'message' =>'Виберіть дату'],
            [['labor', 'status'], 'validateLabor'],

            [['theme_id'], 'validateTheme'],

           // [['nodes'], 'validateNodes'],

        ];
    }

/*
    public function validateNodes(){
        if(Yii::$app->request->get('r') == 'personal-plan/update'){
            $this->addError('nodes', 'Виберіть елемент(и) дерева, по якому(яких) ведеться ця робота');
        }
    }
*/
    public function validateTheme(){
        if(Yii::$app->user->can("system")) {
            if (Theme::findOne($this->theme_id)->status) {
                $this->addError('theme_id', 'Роботи по цій темі закриті для редагування');
            }
        }
    }

    public function validateDate()
    {

        $currentDate = date('Y-m-d');//Yii::$app->getFormatter()->asDate(time());

        if ($this->started_at > $this->finished_at) {
        $this->addError('started_at', 'Перевірте дату закінчення');
        $this->addError('finished_at', 'Дата закінчення не може бути раніше дати початку');
    }

    if($this->finished_at > $this->theme->deadline && Yii::$app->user->can('taskForSector')){
        $this->addError('finished_at', 'Дата закінчення не може бути пізніше дедлайну теми');
    }

  //  if(Yii::$app->user->can('system')){
        if(date("F",strtotime($this->finished_at)) != date("F", strtotime($this->started_at))){
             $this->addError('finished_at', 'Робота повинна закінчитися в той місяць, в якому розпочата. 
             Якщо робота займає більше часу - розбийте роботу, наприклад, таким чином: створіть таку ж саму роботу на інші місяці періоду виконання. Це необхідно для щомісячного звітування.');
        }
   // }
     //   if ($this->isNewRecord) {
           // if ($currentDate > $this->started_at) {
           //     $this->addError('started_at', '"Дата начала", не может быть раньше текущей даты');
           // }

        /*
        if(Yii::$app->user->can("system")) {
            if ($this->started_at_fact < $this->started_at) {
                $this->addError('started_at_fact', 'Фактична дата початку не може бути раніше дати видачі роботи');
            }
        }
*/

        //AHTUNG!
           // if ($currentDate > $this->finished_at_fact && !Yii::$app->user->can('taskForSector') && $this->status == 3) {
           //     $this->addError('finished_at_fact', 'Дата закінчення не може бути раніше за сьогодні');
           //  }

       // if ($this->finished_at_fact && !Yii::$app->user->can('taskForSector') && $this->status == 3) {
       //     $this->addError('finished_at_fact', 'Потрібна дата закінчення');
       // }
       // }
    }

    public function validateDateFact()
    {
        $currentDate = Yii::$app->getFormatter()->asDate(time());



        if($this->status == 3){

            if (!$this->started_at_fact) {
                $this->addError('started_at_fact', 'Неможливо здати роботу без дати початку');
            }

            if (!$this->finished_at_fact){
                $this->addError('phinished_at_fact', 'Неможливо здати роботу без дати закінчення');
            }

            if ($this->started_at_fact > $this->finished_at_fact) {
                $this->addError('started_at_fact', 'Перевірте дату закінчення');
                $this->addError('finished_at_fact', 'Дата закінчення не може бути раніше дати початку!');
            }
        }


        if($this->status == 5 && !$this->started_at_fact){
                $this->addError('started_at_fact', 'Визначте дату початку роботи');
        }
    }


    public function validateLabor(){
        $labor = Basic::getTotalAll($this->id);

        $exec = \frontend\models\ExecutorAssignment::findOne(['job_id'=>$this->id]);
       if($exec && $exec->parent_job_id)return;


       if(round($labor,2) < round($this->labor, 2) && $this->status == 2 && !$this->isExternal()){
            $this->addError('labor', 'Неможливо прийняти роботу без застосування розрахунку трудоміскості виконавцем ');
        }

        if($this->labor <= 0  && $this->status == 2){
            $this->addError('labor', 'Неможливо прийняти роботу без трудоміскості');
        }

       // if(Yii::$app->user->can("system")){
            if($labor < $this->labor && $this->status == 3 && $this->idid){
                $this->addError('labor', 'Неможливо здати роботу без застосування розрахунку трудоміскості');
            }
       // }

        if($this->labor <= 0 && $this->status == 3){
            $this->addError('labor', 'Неможливо здати роботу без визначеної трудоміскості');
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resident_id' => 'Видав',
            'theme_id' => 'Тема',
            'content' => 'Зміст робіт',
            'started_at' => 'Дата початку по плану',
            'finished_at' => 'Дата закінчення по плану',
            'started_at_fact' => 'Дата початку фактична',
            'finished_at_fact' => 'Дата закінчення фактична',
            'labor' => 'Трудомісткість н/г',
            'created_at' => 'Created At',
            'desc' => 'Коментар виконавця',
            'theme_content' => 'Тема',
            'theme_num' => 'Номер теми',
            'executor' => 'Виконавець',
            'status'=>'Cтан',
            'norm' => '',//'Норма',
            'idid' => 'Робота',
            'percent' => 'Відсоток від роботи %',
            'resident_type' => 'Тип виконавця',
            'nodes' => 'Елементи дерева в роботі',
            'norm_percent' => 'Відсоток (робота перевидана) %',
            'norm_percent_labor' => 'перерахунок Трудомісткісті на перевидання н/г'
        ];
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

    public function getSector(){
        return \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id]);
    }

    public function getExecutor(){
        return ExecutorAssignment::findOne(['job_id'=>$this->id, 'status' => 1]);
    }

    public function getSorcerer(){
        return Resident::findOne($this->getExecutor()->sorcerer_id);
    }


    public function getTotal($provider){
        $sum = 0;
        foreach ($provider as $item){
            if($item['status'] == 2) {
                $sum += $item['labor'];
            }
        }
        return $sum;
    }

    // Тут надо добавить ограничение месяцем
    public function getWorkersStr($models){

        return '';
        
        $query = Resident::find();
        $query->andFilterWhere(['=', 'struct_id', 1]);
        $query->andFilterWhere(['=', 'div_id', $this->div]);
        $query->andFilterWhere(['=', 'sector_id', $this->sector]);
        $all = $query->count();

        /////  пересчитываю работников независимо от пагинации

        $query_p = PersonalPlan::find();
        if($this->m == 'current'){
            $query_p->andFilterWhere(['>=','started_at', date('Y-m').'-01']);
            $query_p->andFilterWhere(['<','started_at', date("Y-m", strtotime("+1 month")).'-01']);
        }elseif ($this->m == 'next'){
            $query_p->andFilterWhere(['>=','started_at', date("Y-m", strtotime("+1 month")).'-01']);
        }


        //этот расчет зависим от пагинации
        $in_work = 0;
        $buzzy = 0;
        if($models) {
            $jobs_arr = [];
            $done_arr = [];
            foreach ($models as $plan) {
                $jobs_arr[] = $plan->id;
                if($plan->status != 2){
                    $done_arr[] =  $plan->id;
                }
            }
        //

            $query2 = ExecutorAssignment::find()->select(['resident_id'])->distinct('resident_id');
            $query2->andFilterWhere(['in', 'job_id', $jobs_arr]);

            $in_work = $query2->count();

            $query2->andFilterWhere(['in', 'job_id', $done_arr]);
            $buzzy = $query2->count();
         //   $buzzy = $in_work - sizeof(array_unique($done_arr));
        }

       // $query->andFilterWhere(['in', 'id', $query2]);
      // $query->andFilterWhere(['in', 'id', $models]);
        //$query2->andFilterWhere(['not in', 'job_id', $provider]);


        return 'Штат:'.$all.' Задіяно:'.$in_work.'  Зараз:'.$buzzy;

    }


    protected function getDate($date){
        $date2 = \Yii::$app->formatter->asDate($date, 'yyyy-MM-dd');
        if ($date2 !='<span class="not-set">(not set)</span>'&& $date != '') return $date2;
        else return null;
    }

    public function getExec(){
        return ExecutorAssignment::findOne(['job_id'=>$this->id]);
    }


    public function isExternal(){
        if($assing=$this->getExecutor()) {
            $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
            if ($resident) {
                if (!$resident->type) return false;
                else return true;
            }
            return false;
        }
    }

    public function getJobColor($status){
        if ($status == 2) {
            return '#ddffdd';
        }

        if($status == 3) {
            return '#fff9aa';
        }
        return false;
    }

    //Список елементов дерева
    public function drawElementsList($delimeter = ', '){
        $nodes = JobNode::find()->where(['job_id'=>$this->id])->andWhere(['not', ['node_id' => null]])->all();
        $str = '';
        if(!$nodes) return '';
        foreach ($nodes as $node){
            $str .= $node->node->name.' ('.$node->node->N.')'.$delimeter;
        }
        return $str;
    }

}

