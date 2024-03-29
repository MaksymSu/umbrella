<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MainTree */

$this->title = 'Update Main Tree: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Main Trees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="main-tree-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
