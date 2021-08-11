<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormJob */

$this->title = 'Create Norm Job';
$this->params['breadcrumbs'][] = ['label' => 'Norm Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-job-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
