<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormUnit */

$this->title = 'Create Norm Unit';
$this->params['breadcrumbs'][] = ['label' => 'Norm Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
