<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ThemeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Теми';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавити тему', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'number',
            'step',
            [
                'attribute' => 'content',
                'contentOptions'=>['style'=>'max-width: 300px;white-space: normal;']
            ],
          //  'type',
          //  'born',
          //  'status',
            [
                'attribute' => 'desc',
                'contentOptions'=>['style'=>'max-width: 400px;white-space: normal;'] ,
            ],

           // 'deadline',
            [
                'attribute' => 'deadline',
                'content' => function ($model) {
                    if ($model) return Yii::$app->formatter->asDate($model->deadline, 'dd.MM.yyyy');
                    return '';
                }
            ],

            [
                'attribute' => 'master_div_id',
                'content' => function($model){if($model->div)return $model->div->name;},

            ],

            [
                    'label' => 'Норм.',
                'attribute' => 'no_norms',
                'content' => function($model){if($model->no_norms)return 'без норм';},

            ],

            [
                'label' => 'Блок',
                'attribute' => 'status',
                'format' => 'raw',
                'content' => function($model){if($model->status)return '<i class="glyphicon glyphicon-remove"></i>';},

            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
