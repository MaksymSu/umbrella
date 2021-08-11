<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\MainTreeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="main-tree-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'step') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'born') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'deadline') ?>

    <?php // echo $form->field($model, 'master_div_id') ?>

    <?php // echo $form->field($model, 'no_norms') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
