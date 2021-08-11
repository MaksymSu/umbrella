<?php

namespace frontend\models;

use frontend\models\CdFiles;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use frontend\models\CdFiles;

/**
 * CdFilesSearch represents the model behind the search form of `frontend\models\CdFiles`.
 */
class CdFilesSearch extends CdFiles
{
    /**
     * {@inheritdoc}
     */
    public $job_id_filter;

    public function rules()
    {
        return [
          //  [['id', 'job_id', 'source_id', 'resident_id', 'node_id'], 'integer'],
           // [['sys_name', 'user_name', 'created_at', 'resident', 'system'], 'safe'],
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
        $query = CdFiles::find();

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

        $post = \Yii::$app->request->post();
        $get = \Yii::$app->request->get();
        // grid filtering conditions

        if(isset($_GET['id']))$this->job_id = $_GET['id'];
        if(isset($get['job_id'])){
            $this->job_id = $get['job_id'];
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'job_id' => $this->job_id,
            'source_id' => $this->source_id,
            'created_at' => $this->created_at,
            'resident_id' => $this->resident_id,

        ]);


        $query->andFilterWhere(['like', 'sys_name', $this->sys_name])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

       // $query->andFilterWhere(['in', 'node_id', explode(',', $get['nodes'])]);
        $query->andFilterWhere(['resident_id' => Resident::findOne(['user_id'=>\Yii::$app->user->id])->id]);
        $query->orderBy([
            'created_at' => SORT_DESC
        ]);
        return $dataProvider;
    }
}
