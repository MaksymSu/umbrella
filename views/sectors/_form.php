<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sectors */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="sectors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php $dataStruct = ArrayHelper::map(\frontend\models\Structs::find()->all(),'id','name'); ?>
    <?= $form->field($model, 'struct_id')->dropDownList($dataStruct,
        ['prompt' => '-Виберіть структуру-',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/sectors/setdiv').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.Html::getInputId($model, 'div_id').'" ).html( data );
                            }
                        );',
        ]
    );?>

    <?= $form->field($model, 'div_id')->dropDownList(ArrayHelper::map(\frontend\models\Div::find()->where(['struct_id'=>$model->struct_id])->all(),'id','name'));?>


    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
