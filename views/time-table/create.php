<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TimeTable */

$this->title = 'Добавити';
$this->params['breadcrumbs'][] = ['label' => 'Абривіатура табелю обліку', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-table-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
