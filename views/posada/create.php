<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Posada */

$this->title = 'Create Posada';
$this->params['breadcrumbs'][] = ['label' => 'Posadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posada-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
