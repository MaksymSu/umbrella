<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\PlanForSector;

/**
 * PlanforsectorSearch represents the model behind the search form of `frontend\models\PlanForSector`.
 */
class PlanforsectorSearch extends PlanForSector
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tab', 'age', 'struct_id', 'div_id', 'sector_id', 'user_id'], 'integer'],
            [['fname', 'sname', 'lname', 'dob', 'photo', 'posada_name', 'created_at', 'desc'], 'safe'],
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
        $user_record = Resident::findOne(['user_id' => \Yii::$app->user->id]);
        $query = PlanForSector::find()->where(['sector_id' => $user_record->sector_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'tab' => $this->tab,
            'dob' => $this->dob,
            'age' => $this->age,
            'struct_id' => $this->struct_id,
            'div_id' => $this->div_id,
            'sector_id' => $this->sector_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);



        $query->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'sname', $this->sname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'posada_name', $this->posada_name])
            ->andFilterWhere(['like', 'desc', $this->desc]);


        return $dataProvider;
    }
}
