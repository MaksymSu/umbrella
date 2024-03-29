<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Structs */

$this->title = 'Update Structs: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Structs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="structs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
