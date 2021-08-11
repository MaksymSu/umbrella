<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ResidentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resident-search">

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

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
