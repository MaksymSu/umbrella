<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanForSector */

$this->title = 'Create Plan For Sector';
$this->params['breadcrumbs'][] = ['label' => 'Plan For Sectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-for-sector-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
