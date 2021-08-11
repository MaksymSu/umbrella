<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CdFilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cd Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cd-files-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cd Files', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'job_id',
            'sys_name',
            'user_name',
            'source_id',
            //'created_at',
            //'resident_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
