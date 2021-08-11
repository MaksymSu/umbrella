<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\MainTree;

/**
 * MainTreeSearch represents the model behind the search form of `frontend\models\MainTree`.
 */
class MainTreeSearch extends MainTree
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'step', 'type', 'status', 'master_div_id', 'no_norms'], 'integer'],
            [['number', 'content', 'born', 'desc', 'deadline'], 'safe'],
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
        $query = MainTree::find();

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
            'step' => $this->step,
            'type' => $this->type,
            'born' => $this->born,
            'status' => $this->status,
            'deadline' => $this->deadline,
            'master_div_id' => $this->master_div_id,
            'no_norms' => $this->no_norms,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
