<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanForSector */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plan For Sectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="plan-for-sector-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'fname',
            'sname',
            'lname',
            'tab',
            'dob',
            'age',
            'photo',
            'struct_id',
            'div_id',
            'sector_id',
            'user_id',
            'posada_name',
            'created_at',
            'desc:ntext',
        ],
    ]) ?>

</div>
