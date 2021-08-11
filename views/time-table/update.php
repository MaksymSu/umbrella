<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TimeTable */

$this->title = 'Оновити';// . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Абривіатура табелю обліку', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="time-table-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
