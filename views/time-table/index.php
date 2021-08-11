<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TimeTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Абривіатура табелю обліку';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-table-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавити', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'code',
            [
                'attribute' => 'color',
                'format' => 'raw',
                'value' => function($model){if($model->color)return '<div style="float:left; width: 100px; background-color: '.$model->color.';">&nbsp;</div>'.' '.$model->color;},
            ],
            'about',
            'hours',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
