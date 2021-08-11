<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Norms;

/**
 * NormsSearch represents the model behind the search form of `frontend\models\Norms`.
 */
class NormsSearch extends Norms
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'norm_unit_id', 'novelty_group_id', 'difficulty_group_id', 'status'], 'integer'],
            [['value'], 'number'],
            [['job_code', 'job_name', 'updated_at', 'update_id', 'desc'], 'safe'],
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
        $query = Norms::find();

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
            'value' => $this->value,
            'norm_unit_id' => $this->norm_unit_id,
            'novelty_group_id' => $this->novelty_group_id,
            'difficulty_group_id' => $this->difficulty_group_id,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'job_code', $this->job_code])
            ->andFilterWhere(['like', 'job_name', $this->job_name])
            ->andFilterWhere(['like', 'update_id', $this->update_id])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
