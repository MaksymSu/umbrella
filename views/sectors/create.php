<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sectors */

$this->title = 'Добавити сектор';
$this->params['breadcrumbs'][] = ['label' => 'Сектори', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sectors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
