<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Theme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="theme-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'step')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'deadline')->textInput(['type' => 'date', 'style'=>'width: 250px']) ?>

    <?php
    $all_div_arr = \yii\helpers\ArrayHelper::map(\frontend\models\Div::find()
        ->all(),
        'id','name');
    ?>

    <?= $form->field($model, 'master_div_id')
        ->dropDownList($all_div_arr); ?>


    <?php
   // if(Yii::$app->user->can('system')){
        echo $form->field($model, 'no_norms')
            ->checkBox();
   // }
    ?>

    <?php
    // if(Yii::$app->user->can('system')){
    echo $form->field($model, 'status')
        ->checkBox();
    // }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
