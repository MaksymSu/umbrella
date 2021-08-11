<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\PersonalPlan;

/**
 * PersonalPlanSearch represents the model behind the search form of `frontend\models\PersonalPlan`.
 */
class PersonalPlanMainSearch extends PersonalPlan
{
    /**
     * {@inheritdoc}
     */
    //  public $theme_content;


    public function rules()
    {
        return [
            [['id', 'resident_id', 'theme_id', 'status', 'executor', 'div', 'sector', 'resident_type'], 'integer'],
            [['content', 'created_at', 'desc', 'theme_content','theme_num', 'started_at', 'finished_at'], 'safe'],
            [['labor'], 'double'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PersonalPlan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            'resident_id' => null,//$this->resident_id,
            'theme_id' => $this->theme_id,
        //    'started_at' => $this->started_at,
        //    'finished_at' => $this->finished_at,
            'started_at_fact' => $this->started_at_fact,
            'finished_at_fact' => $this->finished_at_fact,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'labor' => $this->labor,
            //'executor' => $this->executor,

        ]);
        $query->andFilterWhere(['>=', 'started_at', $this->started_at]);//lb
        $query->andFilterWhere(['<=', 'finished_at', $this->finished_at]);//lb

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'desc', $this->desc]);
        //->andFilterWhere(['like', 'started_at', $this->started_at]);

        // фильтруем только текущего нач.сектора
        //if(\Yii::$app->user->can('taskForSector')){
        if (!$this->executor) {

            $residents = Resident::find()->select(['id']);//->
           // where(['div_id' => $this->div, 'sector_id' => $this->sector]);
            if($this->div){
                $residents->andFilterWhere(['=', 'div_id', $this->div]);
            }

            if($this->sector){
                $residents->andFilterWhere(['=', 'sector_id', $this->sector]);
            }

            $jobs = ExecutorAssignment::find()->select(['job_id'])
                ->andFilterWhere(['in', 'resident_id', $residents]);
            //  ->andFilterWhere(['resident_id'=>$this->executor]);
            $query->andFilterWhere(['in', 'id', $jobs]);
        }else{
            $jobs = ExecutorAssignment::find()->select(['job_id'])
                ->where(['resident_id' => $this->executor]);
            //  ->andFilterWhere(['resident_id'=>$this->executor]);
            $query->andFilterWhere(['in', 'id', $jobs]);

        }

        //}
        // MONTH!!
        if(\Yii::$app->user->can('system')){
           // echo  '<script>alert("'.date("Y-m", strtotime("+1 month", strtotime(date("Y-m")))).'")</script>';
        }

        if($this->m == 'current'){
            $query->andFilterWhere(['>=','started_at', date('Y-m').'-01']);
            $query->andFilterWhere(['<','started_at', date("Y-m", strtotime("+1 month", strtotime(date("Y-m")))).'-01']);
        }elseif ($this->m == 'next'){
            $query->andFilterWhere(['>=','finished_at', date("Y-m", strtotime("+1 month",strtotime(date("Y-m")))).'-01']);
        }elseif ($this->m == 'last'){
            $query->andFilterWhere(['<','started_at', date('Y-m').'-01']);
            $query->andFilterWhere(['>=','started_at', date("Y-m", strtotime("-1 month", strtotime(date("F") . "1"))).'-01']);
        }


        $query3 = Themes::find()->select('id')->where(['like', 'content', $this->theme_content]);
        if($this->theme_content != '') {
            $query->andFilterWhere(['in', 'theme_id', $query3]);
        }
        $dataProvider->sort->attributes['theme_content'] = [
            'asc' => ['theme_id' => SORT_ASC],
            'desc' => ['theme_id' => SORT_DESC],
        ];


      //  $query4 = Themes::find()->select('id')->where(['like', 'id', $this->theme_num]);
        $query4 = Themes::find()->select('id')->where(['id'=>$this->theme_num]);


        if($this->theme_num != '') {
            $query->andFilterWhere(['in', 'theme_id', $query4]);
        }
        $dataProvider->sort->attributes['theme_num'] = [
            'asc' => ['theme_id' => SORT_ASC],
            'desc' => ['theme_id' => SORT_DESC],
        ];



        //resident_type
      //  if(\Yii::$app->user->can('system')){

            if (is_numeric($this->resident_type)) {

                $residents = Resident::find()->select(['id'])->where(['type'=>$this->resident_type]);//->

                $jobs = ExecutorAssignment::find()->select(['job_id'])
                    ->andFilterWhere(['in', 'resident_id', $residents]);

                $query->andFilterWhere(['in', 'id', $jobs]);
            }
      //  }
        //////////
        return $dataProvider;
    }



}


