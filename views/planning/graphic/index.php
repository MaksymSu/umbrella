<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\Planning\PersonalPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'План-графік';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="personal-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <div  id="section-to-hide">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
        if(Yii::$app->user->can('planning')) {
            echo Html::a('Добавити роботу', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
    <?php
    if(Yii::$app->user->can('print_plan_pz')){
        // echo Html::a('Друк для ПЗ', ['print'], ['class' => 'btn btn-primary', 'style' => 'float: right']);
        if($theme = \frontend\models\Themes::findOne($searchModel->theme_id_search)) {
            echo '<input type="button" class="btn btn-primary" value="Друк для ПЗ" onClick="window.print()" style="float: right">';
        }
    }
    ?>

    <?php
    if(Yii::$app->user->can('viewPlanning')){
        $filter = Html::activeDropDownList($searchModel, 'theme_id_search',
            \yii\helpers\ArrayHelper::map(
                \frontend\models\Themes::find()
                    ->all()
                , 'id', function ($data) {
                return $data->number . ' - ' . $data->content;
            }),
            ['class' => 'form-control', 'prompt' => 'Всі']);
    }else {
        $filter = Html::activeDropDownList($searchModel, 'theme_id_search',
            \yii\helpers\ArrayHelper::map(
                \frontend\models\Themes::find()->where(['master_div_id' => $searchModel->getMyDiv()->id])
                    ->orFilterWhere(['in', 'id',
                        \frontend\models\Planning\PersonalPlan::find()->select(['theme_id'])->where(['in', 'id',
                            \frontend\models\Planning\ExecDivAssignment::find()->select((['job_id']))->where(['div_id' => $searchModel->getMyDiv()->id])
                        ])
                    ])
                    ->all()
                , 'id', function ($data) {
                return $data->number . ' - ' . $data->content;
            }),
            ['class' => 'form-control', 'prompt' => 'Всі']);
    }

    /////////////////
    $columns =
        [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'state',
                'contentOptions'=>['style'=>'max-width: 40px;white-space: normal;'] ,
                'format'=>'raw',
                'value' => function($model){
                    if($model->isInWork()) {
                        return '<div class="loader555"></div>';
                    }
                    return '';
                }
            ],

            //'id',
            //'resident_id',
            // 'theme_id',

            [
                'attribute'=>'theme_id',
                'contentOptions'=>['style'=>'width: 320px;white-space: normal;'] ,

                'filter'=>$filter,

                'content'=>function($model){
                    if(isset($model->theme))
                        return $model->theme->number.' - '.$model->theme->content;
                    else return '';
                }
            ],
            /*
                        [
                            'attribute' => 'theme_id',
                            'content' => function($model){if($model->theme)return '('.$model->theme->number.') '.$model->theme->content;},
                            'contentOptions'=>['style'=>'max-width: 300px;white-space: normal;'] ,
                        ],
            */


            [
                'attribute' => 'master_div_id',
                'content' => function($model){if($model->theme)return $model->theme->masterDiv->name;},
                'contentOptions'=>['style'=>'max-width: 200px;white-space: normal;'] ,
            ],

           // 'content:ntext',
            [
                'attribute' => 'content',
                'contentOptions'=>['style'=>'max-width: 340px;white-space: normal;'] ,
            ],
                //'started_at',
            //'finished_at',
            //'created_at',
            //'status',
            //'desc',
            [
                'attribute' => 'labor',
                'label' => "Трудомісткість = ". frontend\models\Basic::getTotalLabor2($dataProvider->query->all())." н/г"

            ],
            // 'labor',
            //'started_at_fact',
            //'finished_at_fact',

            [
                'attribute' => 'executor_div_id',
                'content' => function ($model) {
                    $exec_div = \frontend\models\Planning\ExecDivAssignment::findOne(['job_id' => $model->id]);
                    if($exec_div->div){
                        return $exec_div->div->name;
                    }
                },
                'contentOptions'=>['style'=>'max-width: 200px;white-space: normal;'] ,
            ],

            [
                'attribute' => 'executor_sector_id',
                'content' => function ($model) {
                    $exec_sector = \frontend\models\Planning\ExecDivAssignment::findOne(['job_id' => $model->id]);
                    if($exec_sector->sector){
                        return $exec_sector->sector->name;
                    }
                },
                'contentOptions'=>['style'=>'max-width: 200px;white-space: normal;'] ,
            ],


        ];

    if(Yii::$app->user->can('planning')){
        $columns[]=['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',//$searchModel->isInWork() ? '{update} {delete}' : '{update}',
            'urlCreator' => function ($url, $model){
            \yii\helpers\Url::remember();
                return 'index.php?r=planning/'.$url.'&id='.$model->id;
            },
        ];
    }
    ?>




    <p>
        <?php
       // if(Yii::$app->user->can('system')) {
            echo
                \frontend\models\Basic::getTotalTerms($dataProvider);
    //    }
        ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

    </div>

        <?php
        if(Yii::$app->user->can('system'))
            $v = 'visible';
        else $v = 'collapse';
        ?>
        <div id="section-to-print" style="visibility: <?php echo $v; ?>">
            <?php
            if(Yii::$app->user->can('print_pz')){
                if($theme = \frontend\models\Themes::findOne($searchModel->theme_id_search)) {
                    \frontend\models\Basic::drawPlanPrn($dataProvider, $theme->content);
                    //echo '<input type="button" class="btn btn-primary" value="Друкувати" onClick="window.print()">';
                }
            }
            ?>
        </div>


    <?php Pjax::end(); ?>

</div>

<?php


$css = <<< CSS

     .loader555 {
        border: 3px solid #F3F3F3; /* Light grey */
        border-top: 3px solid #337ab7; /* Blue */
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
CSS;
$this->registerCss($css);



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


