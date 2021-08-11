<?php

namespace frontend\controllers;

use frontend\models\NormJob;
use Yii;
use frontend\models\Norm;
use frontend\models\NormSearch;
//use frontend\models\NormJob;
use frontend\models\NormJobSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NormController implements the CRUD actions for Norm model.
 */
class NormController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Norm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelNJ = new NormJobSearch();
        $dataProviderNJ = $searchModelNJ->search(Yii::$app->request->queryParams, null);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelNJ' => $searchModelNJ,
            'dataProviderNJ' => $dataProviderNJ,
           // 'time' => date('H:i:s'),
        ]);
    }

    /**
     * Displays a single Norm model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Norm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Norm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Norm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Norm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDelete2($id)
    {
        if($rec =NormJob::findOne($id)) {
            $rec->delete();
        }
        //var_dump($id);
        //return $this->redirect(['index']);
        //$this->actionIndex();
        $this->drawGrid($id);
    }
    /**
     * Finds the Norm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Norm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Norm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


   public function actionAdd($id){
     //   echo '<script>alert("'.$id.'");</script>';
       // return;
        $rec = new NormJob();
        $rec->norm_id = $id;//Yii::$app->request->get('id');
        //$rec->job_id = Yii::$app->request->get('job_id');
        $rec->save();
       // $this->actionIndex();
       $this->drawGrid($id);

     //  echo $id;
    }

    public function drawGrid($id){

        $searchModelNJ = new NormJobSearch();
        $dataProviderNJ = $searchModelNJ->search(Yii::$app->request->queryParams, null);

    echo GridView::widget([
    'dataProvider' => $dataProviderNJ,
       'filterModel' => $searchModelNJ,
    'columns' => [
        //'id',
        [

            'class' => 'yii\grid\ActionColumn',

            'template' => '{delete}',
            'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],

            'buttons' => [


                'delete' => function ($url, $model, $key) {
                    return Html::button('', [ 'class' => 'btn btn-warning glyphicon glyphicon-minus', 'onclick' =>
                        '$.get( "'.\yii\helpers\Url::toRoute('/norm/delete2').'", { id: '.$model->id.' } )
                            .done(function( data ) {
                        $( "#wwww" ).html( data );
                    }
                    );'
                        //  '(function ( $event ) { alert("Button 3 clicked"); })();'
                    ]);
                   // return  Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete2', 'id'=>$key]);//, ['linkOptions' => ['data-method' => 'post']]);
                }

            ]

        ],



        [
            'label'=> 'Вибрані норми',
            'content' => function($model){
                return $model->norm->name->content;
            }
        ],
        [
            'label'=> 'Новизна',
            'content' => function($model){
                return $model->norm->novelty;
            }
        ],
        [
            'label'=> 'Складність',
            'content' => function($model){
                return $model->norm->difficulty;
            }
        ],
        'norm_id',
        'job_id',

        'format_id',


        //   ['class' => 'yii\grid\ActionColumn'],
    ],
]);

    }

}
