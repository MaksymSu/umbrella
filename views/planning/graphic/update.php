<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Planning\PersonalPlan */

$this->title = 'Редагування роботи';
$this->params['breadcrumbs'][] = ['label' => 'Роботи плану', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагування';
?>
<div class="personal-plan-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= $this->render('_form', [
        'model' => $model,
       // 'back_url' => $back_url,
    ]) ?>

</div>
