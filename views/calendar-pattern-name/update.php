<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\CalendarPatternName */

$this->title = 'Редагувати шаблон: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Шаблони', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагувати';
?>
<div class="calendar-pattern-name-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'create' =>false,
    ]) ?>

</div>
