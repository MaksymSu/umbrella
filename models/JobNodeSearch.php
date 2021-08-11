<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\JobNode;

/**
 * JobNodeSearch represents the model behind the search form of `frontend\models\JobNode`.
 */
class JobNodeSearch extends JobNode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'job_id', 'node_id'], 'integer'],
            [['modified_at'], 'safe'],
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
        $query = JobNode::find();

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
            'job_id' => $this->job_id,
            'node_id' => $this->node_id,
            'modified_at' => $this->modified_at,
        ]);

        return $dataProvider;
    }
}
