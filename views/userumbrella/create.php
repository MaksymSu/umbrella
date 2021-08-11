<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Userumbrella */

$this->title = 'Create Userumbrella';
$this->params['breadcrumbs'][] = ['label' => 'Userumbrellas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userumbrella-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
