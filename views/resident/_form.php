<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//use dosamigos\fileupload\FileUpload;
//use kartik\widgets\ActiveForm; // or yii\widgets\ActiveForm
use kartik\file\FileInput;
//use kartik\sidenav\SideNav;
//use kartik\fileinput\;
/* @var $this yii\web\View */
/* @var $model frontend\models\Resident */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resident-form col-lg-8">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $unused = \frontend\models\Resident::find()->select('user_id')->where(['like', 'user_id',  '']);
    $unused->andFilterWhere(['not like', 'user_id', $model->user_id])
   // var_dump($unused);
    ?>
    <?php
    if(Yii::$app->user->can('setUser')) {
        echo $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(\common\models\User::find()
            ->where(['not in', 'id', $unused])->all(), 'id', 'username'), ['prompt' => '-Виберіть користувача-']);
    }
    ?>

    <?php //var_dump(yii::$app->authManager->getRoles()); ?>
    <?= $form->field($model, 'posada_name')->dropDownList(ArrayHelper::map(yii::$app->authManager->getRoles(),'name','description'),
    ['prompt' => '-Виберіть посаду-'])?>

    <hr>
    <?= $form->field($model, 'sname')->textInput(['max length' => true]) ?>
    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>
    <hr>


    <?php $dataStruct = ArrayHelper::map(\frontend\models\Structs::find()->all(),'id','name'); ?>
    <?= $form->field($model, 'struct_id')->dropDownList($dataStruct,
        ['prompt' => '-Виберіть структуру-',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/resident/setdiv').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.Html::getInputId($model, 'div_id').'" ).html( data );
                            }
                        );',
        ]
    );?>


    <?php $dataDiv = ArrayHelper::map(\frontend\models\Div::find()->where(['struct_id'=>$model->struct_id])->all(),'id','name'); ?>
    <?= $form->field($model, 'div_id')->dropDownList($dataDiv,
        ['prompt' => '-Виберіть структуру-',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/resident/setsector').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.Html::getInputId($model, 'sector_id').'" ).html( data );
                            }
                        );',
        ]
    );?>


    <?= $form->field($model, 'sector_id')->dropDownList(ArrayHelper::map(\frontend\models\Sectors::find()->where(['div_id'=>$model->div_id])->all(),'id','name'),  ['prompt' => '-Виберіть структуру-'])?>
    <hr>

    <?= $form->field($model, 'work_mode')->dropDownList($model->work_modes)?>
    <?= $form->field($model, 'type')->dropDownList($model->types)?>

    <?= $form->field($model, 'tab')->textInput() ?>
    <?= $form->field($model, 'dob')->textInput() ?>
    <?= $form->field($model, 'photo')->textInput(['maxlength' => true]) ?>

    <div id="kuku"></div>

    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options'=>['accept'=>'image/*'],
        'language'=> 'uk',
            'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png'],
            'showUpload' => false,
            'showCaption' => false,
           // 'language'=> "uk-UA",
            //'browseLabel' => 'Вибрати файл',
           // 'removeLabel' => 'Видалити',
            ]
    ])
    ?>

    <?php /*echo $form->field($model, 'file')->label('виберіть файл',['class'=>'btn btn-primary'])
        ->fileInput(['class'=>'sr-only',
            'onchange'=>'$.get( "'.\yii\helpers\Url::toRoute('/resident/upload').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#kuku" ).html( data );
                            }
                        );']) */?>

    <?php /*echo $form->field($model, 'phones')->dropDownList(ArrayHelper::map(\frontend\models\Phone::find()->all(), 'id', 'number'),
        [
            'multiple'=>'multiple',
          //  'class'=>'chosen-select input-md required',
            'prompt' => '-Виберіть номери-',
           'style' => 'height: 200px; width: 200px',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/resident/setsector').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.Html::getInputId($model, 'sector_id').'" ).html( data );
                            }
                        );',
        ]
    );*/?>

    <?php
    if($phone = \frontend\models\Phone::findOne(['resident_id' => $model->id])) {
        echo $form->field($model, 'phones')->textInput(['value' => \frontend\models\Phone::findOne(['resident_id' => $model->id])->number]);
    }else{
        echo $form->field($model, 'phones')->textInput();
    }
    ?>


    <?= $form->field($model, 'desc')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
