<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\TimeTable */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Абривіатура табелю обліку', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="time-table-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Оновити', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'code',
            [
                'attribute' => 'color',
               'format' => 'raw',
                'value' => function($model){if($model->color)return '<div style="width: 100px; background-color: '.$model->color.';">&nbsp;</div>'.' '.$model->color;},
            ],

            'about',
            'hours',
            'created_at',
        ],
    ]) ?>

</div>
