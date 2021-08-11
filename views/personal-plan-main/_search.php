<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlanMainSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personal-plan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'resident_id') ?>

    <?= $form->field($model, 'theme_id') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'started_at') ?>

    <?php // echo $form->field($model, 'finished_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'labor') ?>

    <?php // echo $form->field($model, 'started_at_fact') ?>

    <?php // echo $form->field($model, 'finished_at_fact') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
