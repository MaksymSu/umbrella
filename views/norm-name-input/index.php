<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NormNameInputSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Норми';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-name-input-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Додати норму', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'id',
                'contentOptions'=>['style'=>'width: 60px;white-space: normal;'] ,
            ],
            'code',
            [
                'attribute'=>'content',
                'contentOptions'=>['style'=>'width: 600px;white-space: normal;'] ,
            ],

            [
                    'attribute' => 'unit_id',
                    'content' => function($model){
                        if($model->unit) {
                            return $model->unit->content;
                        }
                        return null;
                    }
            ],

            'updated_at',
            [
                'attribute' => 'status',
                'content' => function($model){
                return $model->statuses[$model->status];
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
