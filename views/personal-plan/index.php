<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonalPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зміст мого персонального плану';
$this->params['breadcrumbs'][] = $this->title;

$m = $searchModel->m;

?>
<div style="text-align: center">
    <span style="background-color: #fff9aa;"> Виконано </span>
    <span style="background-color: #ddffdd;"> Прийнято </span>
    <span style="background-color: #ffcccc;">Порушено термін</span>
</div>

<div class="personal-plan-index" >

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model) {
            if ($model->status == 2) {
                return ['style' => 'background-color: #ddffdd;'];

            }

            if($model->status == 3) {
                return ['style' => 'background-color: #fff9aa;'];
            }

            if($model->finished_at_fact > $model->finished_at){
                return ['style' => 'background-color: #ffcccc;'];
            }

         //   if($model->finished_at > date('Y-m-d') && $model->status != 2){
         //       return ['style' => 'background-color: #ffcccc;'];
        //    }

            if($model->finished_at < date('Y-m-d')){
                return ['style' => 'background-color: #ffcccc;'];
            }

        },

        'columns' => [

            ['class' => 'yii\grid\SerialColumn',
                'contentOptions'=>function ($model){
                    if(!\frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id])->parent_job_id)
                        return ['style'=>'background-color:#5bc0de;color:white'];
                    return ['style'=>'background-color:#337ab7;color:white'];
                },
                ],
           // ['class' => 'yii\grid\ActionColumn',
           //     'template' => '{view} {update}',
           // ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($url, $model) use ($m) {
                    return 'index.php?r=personal-plan/'.$url.'&id='.$model->id.'&m='.$m;
                },
               // 'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{view} {update}',
                'buttons' => [

                    'update' => function ($url, $model) {
                        if($model->status == 2)return null;
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'lead-update'),
                        ]);
                    },


                ],
            ],
            //'id',
            // 'resident_id',

            [
                'attribute'=>'theme_num',
                'contentOptions'=>['style'=>'width: 90px;white-space: normal;'] ,

                'content'=>function($model){
                    if(isset($model->theme))
                        return $model->theme->number;
                    else return '';
                }
            ],
            [
                'attribute'=>'theme_content',
                'contentOptions'=>['style'=>'width: 250px;white-space: normal;'] ,

                'format'=>'html',
                'content'=>function($model){
                    if(isset($model->theme))
                        return str_replace("\n", '<br>', $model->theme->content);
                    else return '';
                }
            ],

            [
            'attribute' => 'content',
                'contentOptions'=>['style'=>'width: 250px;white-space: normal;'] ,
            ],
            /*
        [
            'attribute' => 'started_at',
            'content' => function($model){
            return Yii::$app->formatter->asDate($model->started_at, 'd.M.Y');
            }
        ],
*/
           // 'started_at',
          //  'finished_at',
          //  'started_at_fact',
          //  'finished_at_fact',

            [
                'attribute' => 'started_at',
                'content' => function ($model) {return Yii::$app->formatter->asDate($model->started_at, 'dd.MM.yyyy');},
                'format' => 'html',
            ],
            [
                'attribute' => 'finished_at',
                'content' => function ($model) {return Yii::$app->formatter->asDate($model->finished_at, 'dd.MM.yyyy');},
                'format' => 'html',
            ],
            [
                'attribute' => 'started_at_fact',
                'content' => function ($model) {return Yii::$app->formatter->asDate($model->started_at_fact, 'dd.MM.yyyy');},
                'format' => 'html',
            ],
            [
                'attribute' => 'finished_at_fact',
                'content' => function ($model) {return Yii::$app->formatter->asDate($model->finished_at_fact, 'dd.MM.yyyy');},
                'format' => 'html',

            ],
           // 'labor',

            [
                'attribute' => 'labor',
                'format' => 'raw',
                'content' => function($model){
                       // if(Yii::$app->user->can('system')) {
                            if ($parent_job = \frontend\models\ExecutorAssignment::findOne(['job_id' => $model->id])->parentJob){
                                return $model->labor;//. ' <font color="#337ab7" size="3">&nbsp;&nbsp;&nbsp; <b> '. \frontend\models\PersonalPlan::findOne($parent_job->id)->labor.'</b></font>';
                            }
                        //}
                        return $model->labor;
                }

            ],

            //'created_at',
            //'status',
            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'status',
                'contentOptions'=>['style'=>'width: 120px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'status', $searchModel->statuses,
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content' => function($model){
                    if($model->status) {
                        return $model->statuses[$model->status];
                    }else return '';
                }
            ],
            'desc',

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

