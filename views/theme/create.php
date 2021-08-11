<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Theme */

$this->title = 'Добавити тему';
$this->params['breadcrumbs'][] = ['label' => 'Теми', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
