<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanforsectorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-for-sector-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fname') ?>

    <?= $form->field($model, 'sname') ?>

    <?= $form->field($model, 'lname') ?>

    <?= $form->field($model, 'tab') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'struct_id') ?>

    <?php // echo $form->field($model, 'div_id') ?>

    <?php // echo $form->field($model, 'sector_id') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'posada_name') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
