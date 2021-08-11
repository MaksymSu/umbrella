<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormName */

$this->title = 'Create Norm Name';
$this->params['breadcrumbs'][] = ['label' => 'Norm Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-name-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
