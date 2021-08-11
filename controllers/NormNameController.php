<?php

namespace frontend\controllers;

use frontend\models\Basic;
use frontend\models\ExecutorAssignment;
use frontend\models\Format;
use frontend\models\Norm;
use frontend\models\NormJob;
use frontend\models\NormJobSearch;
use frontend\models\PersonalPlan;
use frontend\models\Resident;
use Yii;
use frontend\models\NormName;
use frontend\models\NormNameSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NormNameController implements the CRUD actions for NormName model.
 */
class NormNameController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $layout = 'sidenav';

    public function behaviors()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }
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
     * Lists all NormName models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->guard(Yii::$app->request->get('id'));

        $searchModel = new NormNameSearch();
        //$searchModel->job_id =

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelNJ = new NormJobSearch();
        $dataProviderNJ = $searchModelNJ->search(null, Yii::$app->request->get('id'));
       // $dataProviderNJ->pagination->pageSize = 6;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'job_id' => Yii::$app->request->get('id'),

            'searchModelNJ' => $searchModelNJ,
            'dataProviderNJ' => $dataProviderNJ,
        ]);
    }

    /**
     * Displays a single NormName model.
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
     * Creates a new NormName model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NormName();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing NormName model.
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
     * Deletes an existing NormName model.
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

    public function actionDelete2($id, $job_id = null)
    {

        if($rec =NormJob::findOne($id)) {
            $rec->delete();
            $this->drawGrid($id, $job_id);// Yii::$app->request->get('job_id'));
            exit();
        }

    }

    /**
     * Finds the NormName model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NormName the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NormName::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




    public function actionAdd($id){
        $model= $this->findModel($id);
        $rec = new NormJob();
        $rec->norm_id = $model->norms[0]->id;
        $rec->job_id = Yii::$app->request->get('job_id');

        if($format_splited = $model->isInUnits($model->norms[0]->name->unit->content)) {
            $rec->format_id = array_search($format_splited, Format::getFormats());
        }
      //  echo '<script>alert("'.$job_id.'")</script>';
        $rec->save();
        $this->drawGrid($id, $rec->job_id);

        exit();
    }

    public function drawGrid($id, $job_id = null){

        //var_dump($job_id);exit();
        $searchModelNJ = new NormJobSearch();
        $dataProviderNJ = $searchModelNJ->search(Yii::$app->request->queryParams, $job_id);

        \Yii::$app->language = 'uk-UK';



        echo '<h3>Вибрані норми: <span  id="hhh">'.Basic::getTotalAll($job_id).'</span> н/г</h3>';
        echo GridView::widget([
            'dataProvider' => $dataProviderNJ,
            //   'filterModel' => $searchModelNJ,
            'columns' => [
                //'id',
                [

                    'class' => 'yii\grid\ActionColumn',

                    'template' => '{delete}',
                    'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::button('', [ 'class' => 'btn btn-default glyphicon glyphicon-remove', 'onclick' =>
                                '$.get( "'.\yii\helpers\Url::toRoute('/norm-name/delete2').'", { id: '.$model->id.', job_id: '.$model->job_id.' } )
            .done(function( data ) {
            $( "#wwww" ).html( data );
            }
            );'
                            ]);
                        }

                    ]

                ],

                [
                    'label'=> 'Номер',
                    'contentOptions'=>['style'=>'width: 100px;white-space: normal;'],
                    'content' => function($model){
                        return $model->norm->name->code;
                    }
                ],

                [
                    'label'=> 'Норма',
                    'contentOptions'=>['style'=>'width: 350px;white-space: normal;'],
                    'content' => function($model){
                        return $model->norm->name->content;
                    }
                ],

                [
                    'label'=> 'Новизна',
                    'format'=> 'html',
                    'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                    'content' => function($model){

                        $model->novelty = array_search($model->norm->novelty, \frontend\models\Norm::$novelties);
                        $model->difficulty = array_search($model->norm->difficulty, \frontend\models\Norm::$difficulties);
                        return  Html::activeDropDownList($model, 'novelty',
                            $model->novelties, ['class'=>'form-control',  'id' => 'nov-'.$model->id,

                                'onchange' =>
                                    '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/novelty').'", { 
                        nov: $(this).val(),
                        dif: '.$model->difficulty.',
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.'
                         
                         
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        );'

                            ]);
                    }
                ],

                [
                    'label'=> 'Складність',
                    'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                    'content' => function($model){
                        $model->novelty = array_search($model->norm->novelty, \frontend\models\Norm::$novelties);
                        $model->difficulty = array_search($model->norm->difficulty, \frontend\models\Norm::$difficulties);
                        return  Html::activeDropDownList($model, 'difficulty',
                            $model->difficulties, ['class'=>'form-control',  'id' => 'dif-'.$model->id,

                                'onchange' =>
                                    '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/novelty').'", { 
                        nov: '.$model->novelty.',
                        dif: $(this).val(),
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.'

                         
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        );'

                            ]);
                    }
                ],
        //        'norm_id',
        //        'job_id',

                //'format_id',
                [
                    'format' => 'html',
                    'attribute' => 'format_id',
                    'contentOptions'=>['style'=>'width: 90px;white-space: normal;'],
                    'content' => function($model){
                        if($model->norm->name->unit) {
                           // $unit = $model->norm->unit->content;
                            $unit = $model->norm->name->unit->content;

                            if ($model->isInUnits($unit)) {
                                return 'Аркуш' . Html::activeDropDownList($model, 'format_id',
                                        $model->units, ['class' => 'form-control', 'id' => 'format-'.$model->id,

                                            'onchange' =>
                                                '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/format').'", { 
                        format_id: $(this).val(),
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.'

                                                 
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        );'

                                        ]);
                            }
                            return $unit;
                        }
                        return null;
                    }
                ],

                [
                   'attribute' => 'value',
                    'content' => function($model){
                        //return '<input type="number" id="value-'.$model->id.'"
                        //        min="1" max="1000" class="form-control" value="'.$model->value.'">';
                        return Html::activeInput('number', $model, 'value', ['class' => 'form-control', 'min'=>1,

                            'onchange' =>
                                '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/value').'", { 
                        value: $(this).val(),
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.',
                       
                                             
                         } )
                            .done(function( data ) {
                                $( "#total-'.$model->id.'" ).html( data );
                            }
                        );'

                            ]);
                    }
                ],

                [
                    'label'=> 'н/г',
                    'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                    'content' => function($model){
                        //return $model->norm->value;
                        return '<div id="total-'.$model->id.'">'.Basic::getTotal($model).'</div>';
                    }
                ],
                //   ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);


    }



    public function actionNovelty($nov, $dif, $norm_job_id, $job_id = null){

        $selected_norm_job = NormJob::findOne($norm_job_id);
        $new_norm_id = Norm::findOne(['name_id'=>$selected_norm_job->norm->name_id,
            'novelty'=>Norm::$novelties[$nov],
            'difficulty'=>Norm::$difficulties[$dif],
            ])->id;
        $selected_norm_job->norm_id = $new_norm_id;
        $selected_norm_job->save();
        $this->drawGrid(null, $job_id);
        exit();
/*
    echo '
    <script>
    alert("'.Norm::$novelties[$nov].' - '.Norm::$difficulties[$dif].'");
    </script>
    ';
*/
    }

    public function actionFormat($format_id, $norm_job_id, $job_id=null){
        $selected_norm_job = NormJob::findOne($norm_job_id);
        $selected_norm_job ->format_id = $format_id;
        $selected_norm_job->save();
        $this->drawGrid(null, $job_id);
        //if(!Yii::$app->user->can('system'))
        exit();
    }

    public function actionValue($value, $norm_job_id, $job_id=null){
        $selected_norm_job = NormJob::findOne($norm_job_id);
        $selected_norm_job ->value = $value;
        $selected_norm_job->save();
     //   $this->drawGrid(null, $job_id);
        $this->drawRow($norm_job_id, $value, $job_id);
      //  exit();
    }

    protected function drawRow($norm_job_id, $value, $job_id = null){
      //  $norm_job = NormJob::findOne($norm_job_id);
        echo '
        <script>
        document.getElementById("total-'.$norm_job_id.'").textContent = "'.Basic::getTotal(NormJob::findOne($norm_job_id)).'";
        document.getElementById("hhh").textContent = "'.Basic::getTotalAll($job_id).'";
        </script>
        ';
    }

    public function actionSet($job_id, $m){
        if(!Yii::$app->user->can('planning'))
        $this->guard($job_id);

    if($m == 'master'){
        $job= \frontend\models\Planning\PersonalPlan::findOne($job_id);
    }else {
        $job = \frontend\models\Planning\PersonalPlan::findOne($job_id);
    }
        $job->labor = Basic::getTotalAll($job_id);
        $job->save();


        if(Yii::$app->user->can('planning') && $m == 'master')
            return $this->redirect(['planning/update', 'id' => $job_id, 'm'=>$m]);

        return $this->redirect(['personal-plan/update', 'id' => $job_id, 'm'=>$m]);

    }

    public function actionSet2($job_id, $m){
        if(!Yii::$app->user->can('planning'))
        $this->guard($job_id);

        if(Yii::$app->user->can('planning') && $m == 'master')
            return $this->redirect(['planning/update', 'id' => $job_id, 'm'=>$m]);

        return $this->redirect(['personal-plan/update', 'id' => $job_id, 'm'=>$m]);
    }

    protected function guard($job_id){
        if(Yii::$app->user->can('planning'))return null;
        $assign = ExecutorAssignment::findOne(['job_id'=>$job_id]);
 /////!!!!!!
        if(!$assign)return;


        $executor = Resident::findOne($assign->resident_id);
        if($executor->user_id != Yii::$app->user->id || $assign->job->status == 2){
            return $this->redirect(['personal-plan/index']);
        }

        if(PersonalPlan::findOne($job_id)->status == 2){
            return $this->redirect(['personal-plan/index']);
        }
    }

    public function actionInstruction(){
        //return $this->render('instruction');
        echo '<img src="images/instructions/calc.jpg">';
        exit();
    }

}