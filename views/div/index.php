<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DivSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Відділи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="div-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавити відділ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    //var_dump($searchModel->getStructName());
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',

            'name',
           // 'struct_id',
            [
                'attribute'=>'struct_name',
                'content'=>function($model){
                    return $model->struct->name;
                }
            ],
            'desc:ntext',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
