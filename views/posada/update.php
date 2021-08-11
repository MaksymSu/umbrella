<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Posada */

$this->title = 'Update Posada: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="posada-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
