<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model frontend\models\Norms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="norms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'job_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'norm_unit_id')->dropDownList(ArrayHelper::map(\frontend\models\NormUnit::find()->all(),'id','content'),  ['prompt' => '-Виберіть-'])?>
    <?= $form->field($model, 'novelty_group_id')->dropDownList(ArrayHelper::map(\frontend\models\NoveltyGroup::find()->all(),'id','content'),  ['prompt' => '-Виберіть-'])?>
    <?= $form->field($model, 'difficulty_group_id')->dropDownList(ArrayHelper::map(\frontend\models\DifficultyGroup::find()->all(),'id','content'),  ['prompt' => '-Виберіть-'])?>





    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'update_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
