<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\CalendarPatternName */

$this->title = 'Create Calendar Pattern Name';
$this->params['breadcrumbs'][] = ['label' => 'Calendar Pattern Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-pattern-name-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'create' =>true,
    ]) ?>

</div>
