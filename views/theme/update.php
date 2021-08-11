<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Theme */

$this->title = 'Редагувати тему: ' . $model->content;
$this->params['breadcrumbs'][] = ['label' => 'Теми', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => mb_substr($model->content,0,20).'...', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагування';
?>
<div class="theme-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
