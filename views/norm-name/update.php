<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormName */

$this->title = 'Update Norm Name: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Norm Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="norm-name-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
