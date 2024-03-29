<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PlanforsectorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan For Sectors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-for-sector-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Plan For Sector', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'sname',
            'fname',
            'lname',
            //'tab',
            //'dob',
            //'age',
            //'photo',
            //'struct_id',
            //'div_id',
            //'sector_id',
            //'user_id',
            //'posada_name',
            //'created_at',
            //'desc:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
