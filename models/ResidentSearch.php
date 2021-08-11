<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Resident;

/**
 * ResidentSearch represents the model behind the search form of `frontend\models\Resident`.
 */
class ResidentSearch extends Resident
{
    /**
     * {@inheritdoc}
     */
    public $m;

    public function rules()
    {
        return [
            [['id', 'tab', 'age', 'struct_id', 'div_id', 'sector_id', 'type'], 'integer'],
            [['fname', 'sname', 'lname', 'dob', 'photo','struct_name', 'div_name', 'sector_name'], 'safe'],
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
        $query = Resident::find();

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
        ]);

        $query->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'sname', $this->sname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'photo', $this->photo])
        ->andFilterWhere(['like', 'type', $this->type]);


        //Добавление новой фильтрации и сортировки
     //   $query2 = Structs::find()->select('id')->where(['like', 'name', $this->struct_name]);
     //   if($this->struct_name != '') {
     //       $query->andFilterWhere(['in', 'struct_id', $query2]);
     //   }

        $dataProvider->sort->attributes['struct_name'] = [
            'asc' => ['resident.id' => SORT_ASC],
            'desc' => ['resident.id' => SORT_DESC],
        ];



      //  $query3 = Div::find()->select('id')->where(['like', 'name', $this->div_name]);
      //  if($this->div_name != '') {
     //       $query->andFilterWhere(['in', 'div_id', $query3]);
     //   }

        $dataProvider->sort->attributes['div_name'] = [
            'asc' => ['resident.id' => SORT_ASC],
            'desc' => ['resident.id' => SORT_DESC],
        ];



      //  $query4 = Sectors::find()->select('id')->where(['like', 'name', $this->sector_name]);
     //   if($this->sector_name != '') {
     //       $query->andFilterWhere(['in', 'sector_id', $query4]);
     //   }

        $dataProvider->sort->attributes['sector_name'] = [
            'asc' => ['resident.id' => SORT_ASC],
            'desc' => ['resident.id' => SORT_DESC],
        ];
///////////////////////////////

        return $dataProvider;
    }
}
