<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="norm-unit-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>




    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
