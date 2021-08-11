<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\MainTree */

$this->title = 'Create Main Tree';
$this->params['breadcrumbs'][] = ['label' => 'Main Trees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-tree-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
