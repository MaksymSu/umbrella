<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NormsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Norms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Norms', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'value',
            'job_code',
            'job_name',
            'norm_unit_id',
            'novelty_group_id',
            'difficulty_group_id',
            'updated_at',
            'status',
            'update_id',
            'desc:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
