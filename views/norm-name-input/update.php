<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormNameInput */

$this->title = 'Редагувати: ' . $model->content;
$this->params['breadcrumbs'][] = ['label' => 'Норми', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->content, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагування';
?>
<div class="norm-name-input-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
