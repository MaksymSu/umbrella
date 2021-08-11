<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Norms */

$this->title = 'Create Norms';
$this->params['breadcrumbs'][] = ['label' => 'Norms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norms-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
