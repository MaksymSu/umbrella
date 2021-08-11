<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Norm;

/**
 * NormSearch represents the model behind the search form of `frontend\models\Norm`.
 */
class NormSearch extends Norm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name_id', 'unit_id', 'difficulty', 'status'], 'integer'],
            [['value'], 'number'],
            [['novelty', 'updated_at', 'difficulty'], 'safe'],
            [['novelty_str'], 'string'],
            [['difficulty_str', 'content_str'], 'string'],

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


        $query = Norm::find();

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
            'name_id' => $this->name_id,
            'value' => $this->value,
            'unit_id' => $this->unit_id,
            'difficulty' => $this->difficulty,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
          //  'novelty' => $this->novelty,
        ]);


        if($this->content_str != '') {
            $cond = NormName::find()->select('id')->where(['like', 'content', $this->content_str]);
            $query->andFilterWhere(['in', 'name_id', $cond]);
        }

        $n='А';
        if($this->novelty_str != '') {
            $n =  $this->novelties[$this->novelty_str];
        }
            $query->andFilterWhere(['like', 'novelty', $n]);


        $d = 1;
        if($this->difficulty_str != '') {
            $d =  $this->difficulties[$this->difficulty_str];
        }
            $query->andFilterWhere(['like', 'difficulty', $d]);


        return $dataProvider;
    }
}
