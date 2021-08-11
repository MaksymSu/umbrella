<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonalPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Індивідуальні плани по сектору';
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

    <p>
        <?= Html::a('Добавити роботу', ['create' , 'm'=>$m], ['class' => 'btn btn-info']) ?>

        <?php
          //  if(Yii::$app->user->can('system')){
                echo Html::a('Добавити роботу з план-графіка', ['create2' , 'm'=>$m], ['class' => 'btn btn-primary']);
          //  }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
       // 'tableOptions'=>['class'=>'col-md-10'] ,

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

            if($model->finished_at < date('Y-m-d')){
                return ['style' => 'background-color: #ffcccc;'];
            }

          //  if($model->finished_at > date('Y-m-d') && $model->status != 2){
         //       return ['style' => 'background-color: #ffcccc;'];
         //   }

        },

        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'contentOptions'=>function ($model){
                if(!\frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id])->parent_job_id)
                return ['style'=>'background-color:#5bc0de;color:white'];
                return ['style'=>'background-color:#337ab7;color:white'];
                },
            ],
            [
                    'class' => 'yii\grid\ActionColumn',
                    //'template' => '{view} {update} {delete}',
                    'urlCreator' => function ($url, $model) use ($m) {
                        return 'index.php?r=personal-plan-sector/'.$url.'&id='.$model->id.'&m='.$m;
                    },
/*
                'buttons' => [
                    'update' => function ($url, $model){
                        if(!\frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id])->parent_job_id)
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'lead-update'),
                        ]);
                        return '&nbsp;&nbsp;&nbsp;&nbsp;';
                    },

                ],
*/
    /* 'buttons' => [
         'view' => function ($url, $model) use ($m){
             $url .= $m;//'&m='.Yii::$app->request->get('m');
             return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                 $url, [
                     'title' => Yii::t('app', 'lead-view'),
                 ]);
         },

         'update' => function ($url, $model) use ($m){
             $url .= $m;
             return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                 'title' => Yii::t('app', 'lead-update'),
             ]);
         },





                ],
*/
            ],


           // 'id',
            // 'resident_id',

            [
                'attribute'=>'theme_num',
                'contentOptions'=>['style'=>'max-width: 100px;white-space: normal;'] ,

                'content'=>function($model){
                    if(isset($model->theme))
                        return $model->theme->number;
                    else return '';
                }
            ],
            [
                'attribute'=>'theme_content',
                'format'=>'text',
             //   'contentOptions'=>['style'=>'white-space: normal;'] ,
                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,

                'content'=>function($model){
                    if(isset($model->theme))
                        return str_replace("\n", '<br>', $model->theme->content);
                    else return '';
                }
            ],
            [
            'attribute'=>'content',
                'format'=>'ntext',
                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,

            ],
            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'executor',

                'filter'=>Html::activeDropDownList($searchModel, 'executor',
                    \yii\helpers\ArrayHelper::map(
                            \frontend\models\Resident::findAll(['sector_id' =>
                                $searchModel->getSector()->sector_id
                            ])
                            , 'id', 'sname'),
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content' => function($model){
                    if($assing=$model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);

                        if ($resident) {

                            if($assing = $model->getExecutor()) {
                                $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                                if ($resident)
                                    //$fio = $resident->sname . " " . $resident->fname. " " . $resident->lname;
                                    //$month = '06';
                                    $m = Yii::$app->request->get('m');
                                if($m == 'last'){
                                    $month = date("m", strtotime("-1 months"));
                                } else if($m == 'next'){
                                    $month = date("m", strtotime("+1 months"));
                                } else {
                                    $month = date("m");
                                }

                                $add = '';
                                // if(Yii::$app->user->can('system')){
                                $add .= '<div style="margin-top: 10px">';
                                $add .= \frontend\models\Basic::getZagruskaHtml3($assing->resident_id, $month);
                                $add .= '</div>';

                                //  }
                            }

                            return $resident->sname . "<br>" . $resident->fname . " " . $resident->lname . $add;
                        }


                        return '>Невідомий<';
                    }
                    return null;
                }
            ],
            /*
        [
            'attribute' => 'started_at',
            'content' => function($model){
            return Yii::$app->formatter->asDate($model->started_at, 'd.M.Y');
            }
        ],
*/
          //  'started_at',
          //  'finished_at',
         //   'started_at_fact',
          //  'finished_at_fact',
            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'resident_type',
                'contentOptions'=>['style'=>'width: 120px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'resident_type', $searchModel->resident_types,
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content' => function($model){
                    // if(Yii::$app->user->can('system')) {
                    if($assing=$model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                        if ($resident) {
                            if (!$resident->type) return 'Вн.';
                            else return '<b>Зовн.</b>';
                        }
                        return '>Невідомий<';
                    }
                    //<i class="glyphicon glyphicon-tower" style="margin-left: 30%"></i>';
                    // }
                    return '';
                }
            ],


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

/*
            [
                'attribute' => 'labor',
                'label' => "Трудоміскість - ". $searchModel->getTotal($dataProvider->models)." л/год"

            ],
*/
            [
                'attribute' => 'labor',
                'label' => "Трудомісткість = ". $searchModel->getTotal($dataProvider->query->all())." н/г",
                'format' => 'raw',
                'content' => function($model){
                    // if(Yii::$app->user->can('system')) {
                    if ($parent_job = \frontend\models\ExecutorAssignment::findOne(['job_id' => $model->id])->parentJob){
                        return $model->labor. ' <font color="#337ab7">&nbsp;&nbsp;&nbsp; <b> '. \frontend\models\PersonalPlan::findOne($parent_job->id)->labor.'</b></font>';
                    }
                    // }
                    return $model->labor;
                }

            ],
            //'created_at',
            //'status',
            //'desc',
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

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

