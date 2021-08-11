<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormJob */

$this->title = 'Update Norm Job: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Norm Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="norm-job-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
