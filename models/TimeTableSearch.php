<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TimeTable;

/**
 * TimeTableSearch represents the model behind the search form of `frontend\models\TimeTable`.
 */
class TimeTableSearch extends TimeTable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','hours'], 'integer'],
            [['code', 'color', 'about', 'created_at'], 'safe'],
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
        $query = TimeTable::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'about', $this->about])
        ->andFilterWhere(['=', 'hours', $this->hours]);

        return $dataProvider;
    }
}
