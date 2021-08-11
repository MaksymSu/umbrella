<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonalPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'План-звіт';


$this->params['breadcrumbs'][] = $this->title;
$m = $searchModel->m;
?>



<div class="personal-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <div  id="section-to-hide">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div style="text-align: center">
    <span style="background-color: #fff9aa;"> Виконано </span>
    <span style="background-color: #ddffdd;"> Прийнято </span>
    <span style="background-color: #ffcccc;">Порушено термін</span>
</div>

    <?php
    if(Yii::$app->user->can('print_pz')){
       // echo Html::a('Друк для ПЗ', ['print'], ['class' => 'btn btn-primary', 'style' => 'float: right']);
        if($theme = \frontend\models\Themes::findOne($searchModel->theme_num)) {
            echo '<input type="button" class="btn btn-primary" value="Друк для ПЗ" onClick="window.print()" style="float: right">';
        }
    }


    if(Yii::$app->user->can('print_by_div') && !$searchModel->theme_num && $searchModel->div){
        echo '<input type="button" class="btn btn-primary" value="Друк для ПЗ відділ\теми" onClick="window.print()" style="float: right">';
    }
    ?>

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

       //    if($model->finished_at_fact > date('Y-m-d')){
       //         return ['style' => 'background-color: #ffcccc;'];
       //     }

            if($model->finished_at < date('Y-m-d')){
                return ['style' => 'background-color: #ffcccc;'];
            }

        },

        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'contentOptions'=>function ($model){
                if($model->theme && $model->theme->no_norms)return ['style'=>'background-color:#966;color:white'];
                    if(!\frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id])->parent_job_id)
                        return ['style'=>'background-color:#5bc0de;color:white'];
                    return ['style'=>'background-color:#337ab7;color:white'];
                },
            ],
           // ['class' => 'yii\grid\SerialColumn'],
            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($url, $model) use ($m) {
                    return 'index.php?r=personal-plan-main/'.$url.'&id='.$model->id.'&m='.$m;
                },

                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{view}',
            ],

            // 'id',
            // 'resident_id',

            [
                'attribute'=>'theme_num',
                'contentOptions'=>['style'=>'width: 140px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'theme_num',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Themes::find()->all()
                        , 'id', function ($data) {
                                $theme = \frontend\models\Themes::findOne($data->id);
                            if($theme->deadline >= date('Y-m-d')){
                                return '! '.$data->number . ' - ' . $data->content.' (' .$theme->deadline.')';
                            }
                        return $data->number . ' - ' . $data->content.' (' .$theme->deadline.')';
                    }),
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content'=>function($model){
                    if(isset($model->theme))
                        return $model->theme->number;
                    else return '';
                }
            ],
            [
                'attribute'=>'theme_content',
                'format'=>'ntext',
                'contentOptions'=>['style'=>'width: 250px;white-space: normal;'] ,

                'content'=>function($model){
                    if(isset($model->theme)) {
                       // return str_replace("\n", '<br>', $model->theme->content);
                        return mb_substr($model->theme->content, 0, 100).' ...';
                    }
                    else return '';
                }
            ],
            [
                'attribute'=>'content',
                'format'=>'ntext',
                'contentOptions'=>['style'=>'width: 250px;white-space: normal;'] ,
               // 'content'=> function($model){return mb_substr($model->content, 0, 100).' ...';},
                'content'=> function($model){
                    if($model->desc) {
                        return $model->content . ' > детально > ' . $model->desc;
                    }
                    return $model->content;
                    },


            ],

            [
              'attribute' => 'div',
                'label' => 'Відділ',
                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'div',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Div::findAll(['struct_id' => 1
                        ])
                        , 'id', 'name'),
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content' => function($model){
                    if($assing=$model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                        if ($resident)
                            return \frontend\models\Div::findOne($resident->div_id)->name;
                        return '>Невідомий<';
                    }
                    return null;
                }
            ],

            [
                'attribute' => 'sector',
                //'label' => '',
                'label' => "Сектор ",//. $searchModel->getWorkersStr($dataProvider->models),

                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'sector',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Sectors::findAll(['struct_id' => 1, 'div_id' => $searchModel->div
                        ])
                        , 'id', 'name'),
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content' => function($model){
                    if($assing=$model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                        if ($resident)
                            return \frontend\models\Sectors::findOne($resident->sector_id)->name;
                        return '>Невідомий<';
                    }
                    return null;
                }
            ],

            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'executor',
                //'format' => 'text',
                'label' => "Виконавці  ". $searchModel->getWorkersStr($dataProvider->query->all()),

                'contentOptions'=>['style'=>'width: 160px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'executor',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Resident::findAll(['sector_id' =>
                            $searchModel->sector
                        ])
                        , 'id', 'sname'),
                    ['class'=>'form-control','prompt' => 'Всі']),

                'content' => function($model){
                    if($assing=$model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                        if ($resident)
                            return $resident->sname . "<br>" . $resident->fname. " " . $resident->lname;
                        return '>Невідомий<';
                    }
                    return null;
                }
            ],


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


            /*
        [
            'attribute' => 'started_at',
            'content' => function($model){
            return Yii::$app->formatter->asDate($model->started_at, 'd.M.Y');
            }
        ],
*/
         //   'started_at',
            [
                'attribute' => 'started_at',
                'content' => function ($model) {
                    if ($model) return Yii::$app->formatter->asDate($model->started_at, 'dd.MM.yyyy');
                   // if ($model) return Html::input('date', 'started_at');
                    return '';
                },

                'filter'=>Html::activeInput('date', $searchModel, 'started_at', ['class'=>'form-control', 'style'=>'width:114px']),


            ],
           // 'finished_at',
            [
                'attribute' => 'finished_at',
                'content' => function ($model) {
                    if ($model) return Yii::$app->formatter->asDate($model->finished_at, 'dd.MM.yyyy');
                    return '';
                },
                'filter'=>Html::activeInput('date', $searchModel, 'finished_at', ['class'=>'form-control', 'style'=>'width:114px']),
            ],
           // 'started_at_fact',
            [
                'attribute' => 'started_at_fact',
                'content' => function ($model) {
                    if ($model) return Yii::$app->formatter->asDate($model->started_at_fact, 'dd.MM.yyyy');
                    return '';
                },
               // 'filter'=>Html::activeInput('date', $searchModel, 'started_at_fact', ['class'=>'form-control', 'style'=>'width:114px']),

            ],
            //'finished_at_fact',
            [
                'attribute' => 'finished_at_fact',
                'content' => function ($model) {
                    if ($model) return Yii::$app->formatter->asDate($model->finished_at_fact, 'dd.MM.yyyy');
                    return '';
                },
              //  'filter'=>Html::activeInput('date', $searchModel, 'finished_at_fact', ['class'=>'form-control', 'style'=>'width:114px']),

            ],
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
    </div>

    <?php
    if(Yii::$app->user->can('system'))
    $v = 'visible';
    else $v = 'collapse';

    echo date('Y-m', strtotime(date('Y-m-d')));
    ?>
    <div id="section-to-print" style="visibility: <?php echo $v; ?>">
    <?php
    if(Yii::$app->user->can('print_pz')){
        if($theme = \frontend\models\Themes::findOne($searchModel->theme_num)) {
            \frontend\models\Basic::drawZvitPrn($dataProvider, $theme->content);
            //echo '<input type="button" class="btn btn-primary" value="Друкувати" onClick="window.print()">';
        }
    }


    if(Yii::$app->user->can('print_by_div') && !$searchModel->theme_num && $searchModel->div){
        \frontend\models\Basic::drawZvitPoTemamForDiv($dataProvider, $searchModel->div);
    }
    ?>
    </div>

    <?php Pjax::end(); ?>
</div>

<?php
$script = <<< JS


JS;
$this->registerJs($script, yii\web\View::POS_END);


$css2 = <<< CSS

@media print {
  body * {
    visibility: hidden;
  }
  #section-to-print, #section-to-print * {
    visibility: visible;
    font-size: 14px;
  }
  
  #section-to-print h4{
  font-size: 18px;
  }
  
    #section-to-print .hhh{
  font-size: 16px;
  }
  
  
  .col-md-2 {
  display: none;
  }
  
  #section-to-hide, #section-to-hide * {
    display: none;
  }
  
  .breadcrumb {
  display: none;
  }
  h1 {
  display: none;
  }
    
@page {
    size: auto;   /* auto is the initial value */
    margin-left: 30px;
    margin-right: 40px;
}
}



CSS;

$this->registerCss($css2);

?>
