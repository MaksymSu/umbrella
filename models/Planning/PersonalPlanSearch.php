<?php

namespace frontend\models\Planning;

use frontend\models\ExecutorAssignment;
use frontend\models\Themes;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Planning\PersonalPlan;

/**
 * PersonalPlanSearch represents the model behind the search form of `frontend\models\Planning\PersonalPlan`.
 */
class PersonalPlanSearch extends PersonalPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'resident_id', 'theme_id', 'status', 'theme_id_search'], 'integer'],
            [['content', 'started_at', 'finished_at', 'created_at', 'desc', 'started_at_fact', 'finished_at_fact'], 'safe'],
            [['labor'], 'number'],
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
        $mydiv = $this->getMyDiv();
        if(\Yii::$app->user->can('viewPlanning')){
            $themes = Themes::find()->select(['id']);
        }else {
            $themes = Themes::find()->select(['id'])->where(['master_div_id' => $mydiv->id]);
        }
        $jobs = ExecDivAssignment::find()->select((['job_id']))->where(['div_id' => $mydiv->id]);
        $query = PersonalPlan::find();
        $query->andFilterWhere(['in', 'id', $jobs]);
        $query->orFilterWhere(['in', 'theme_id', $themes]);



        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
           // 'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions


        $query->andFilterWhere([
            'id' => $this->id,
            'theme_id' => $this->theme_id,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'labor' => $this->labor,
            'started_at_fact' => $this->started_at_fact,
            'finished_at_fact' => $this->finished_at_fact,

        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['>', 'resident_id', 0])
            ->andFilterWhere(['theme_id' => $this->theme_id_search]);

      //  $query->andFilterWhere(['in', 'id', ExecutorAssignment::find()->select(['job_id'])]);


        return $dataProvider;
    }
}
