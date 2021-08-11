<?php

namespace frontend\models;

use function GuzzleHttp\Promise\is_settled;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Div;

/**
 * DivSearch represents the model behind the search form of `frontend\models\Div`.
 */
class DivSearch extends Div
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'struct_id', 'status'], 'integer'],
            [['name', 'desc', 'struct_name'], 'safe'],
           // [['name', 'desc'], 'safe'],

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
        $query = Div::find();

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
            'struct_id' => $this->struct_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);
//Добавление новой фильтрации и сортировки
        $query2 = Structs::find()->select('id')->where(['like', 'name', $this->struct_name]);

       if($this->struct_name != '') {
           $query->andFilterWhere(['in', 'struct_id', $query2]);
       }

        $dataProvider->sort->attributes['struct_name'] = [
            'asc' => ['div.id' => SORT_ASC],
            'desc' => ['div.id' => SORT_DESC],
        ];
///////////////////////////////
        return $dataProvider;
    }
}
