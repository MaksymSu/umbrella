<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Planning\PersonalPlan */

$this->title = 'Create Personal Plan';
$this->params['breadcrumbs'][] = ['label' => 'Personal Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
