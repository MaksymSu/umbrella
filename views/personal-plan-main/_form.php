<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personal-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'resident_id')->textInput() ?>

    <?= $form->field($model, 'theme_id')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'started_at')->textInput() ?>

    <?= $form->field($model, 'finished_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'labor')->textInput() ?>

    <?= $form->field($model, 'started_at_fact')->textInput() ?>

    <?= $form->field($model, 'finished_at_fact')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
