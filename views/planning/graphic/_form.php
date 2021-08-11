<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */
/* @var $form yii\widgets\ActiveForm */

/*
if(Yii::$app->user->can('system')){
    echo '<h3>'.\yii\helpers\Url::previous().'</h3>';
}
*/
?>

<?php
$myDiv = $model->getMyDiv();

if(\frontend\models\Planning\ExecDivAssignment::findOne(['job_id'=>$model->id])->master_div_id != $myDiv->id){
    $dis = true;
    $display = 'none';
}
else {
    $dis = false;
    $display = 'block';
}
?>



<div class="personal-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $query = \frontend\models\Theme::find();



   // if($myDiv)

    if(!Yii::$app->user->can('s2ystem')) {
        $query->andFilterWhere(['master_div_id' =>
           $myDiv->id]);

    }



    $all_themes_arr = ArrayHelper::map($query->all(),
        'id',function ($data){return $data->number.' - '.$data->content;});
    ?>

    <?= $form->field($model, 'theme_id')
    ->dropDownList($all_themes_arr, ['disabled'=>$dis,
        'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/planning/set-theme').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).html( data );
                            }
                        );',

        ]); ?>

    <?php
    if(is_null($model->executor_div_id)) {
        $opt = [$myDiv->id => ['Selected' => 'selected']];
    }else $opt=[];
    ?>

    <div style="display: <?= $display?>">

    <?= $div_view = $form->field($model, 'executor_div_id')
        ->dropDownList(ArrayHelper::map(\frontend\models\Div::find()->all(),//->where(['!=','id',$myDiv->id])->all(),
            'id','name'), ['options' => $opt, 'style'=>'margin-right:20px', //'readonly'=>false,//$dis,

                'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/planning/set-sector').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'executor_sector_id').'" ).html( data );
                            }
                        );',
                ]
        ); ?>

    </div>


        <?php
            if($model->executor_div_id != $myDiv->id){
                $style = 'display:none';
            }else {
                $style = 'display:block';
            }
        ?>
        <span id="lb-sector" style="<?= $style ?>">
        <?= $form->field($model, 'executor_sector_id')
        ->dropDownList(ArrayHelper::map(\frontend\models\Sectors::find()->where(['div_id' => $model->executor_div_id])->all(),
            'id','name')
        ); ?>
        </span>
    </div>




    <?= $form->field($model, 'content')->textarea(['rows' => 3, 'disabled'=>$dis]); ?>

    <?php

    $label_start = 'Дата початку по факту (по плану: '.Yii::$app->formatter->asDate($model->started_at, 'dd.MM.yyyy').')';
    $label_finish = 'Дата закінчення по факту (по плану: '.Yii::$app->formatter->asDate($model->finished_at, 'dd.MM.yyyy').')';

    echo $form->field($model, 'started_at')->textInput(['type' => 'date', 'style'=>'width: 250px;display:none'])->label('');
      ;//  ->label($label_start,['class'=>'label-class']);

    echo $form->field($model, 'finished_at')->textInput(['type' => 'date', 'style'=>'width: 250px;display:none'])->label('');
       ;// ->label($label_finish,['class'=>'label-class']);

    ?>
    <div class="form-inline">

        <?php
        $ddd= true;
       // if(Yii::$app->user->can('system')){
            if($model->theme && $model->theme->no_norms){
                $ddd = false;
            }
      //  }
        ?>
    <?= $form->field($model, 'labor')->textInput(['maxlength' => true, 'style'=>'width: 100px', 'disabled' => $ddd]); ?>
    <?php
    if($model->isInWork()) {
        $dd = true;
        $show = 'display:none';
    }else {
        $dd = false;
        $show = '';
    }

    if(Yii::$app->user->can('updateTerms') || Yii::$app->user->can('planning')) {
        echo Html::a('Калькулятор трудоміскості', ['norm-name/index', 'id' => $model->id, 'm' => 'master'], ['class' => 'btn btn-primary',
            'style'=>'margin-bottom:10px; '.$show, 'disabled'=>$dd,

            'onclick' => '
            
            $.get( "'.\yii\helpers\Url::toRoute('/planning/save-form').'", {
             id: '.$model->id.',
             theme_id: $( "#'.\yii\helpers\Html::getInputId($model, 'theme_id').'" ).val(),
             div_id: $( "#'.\yii\helpers\Html::getInputId($model, 'executor_div_id').'" ).val(),
             sector_id: $( "#'.\yii\helpers\Html::getInputId($model, 'executor_sector_id').'" ).val(),
             content: $( "#'.\yii\helpers\Html::getInputId($model, 'content').'" ).val(),
             started_at: $( "#'.\yii\helpers\Html::getInputId($model, 'started_at').'" ).val(),
             finished_at: $( "#'.\yii\helpers\Html::getInputId($model, 'finished_at').'" ).val(),
             dis: '.((int)$dis).'
              } )
                            .done(function( data ) {
                                //$( "#'.\yii\helpers\Html::getInputId($model, 'executor_sector_id').'" ).html( data );
                               // alert("'.((int)$dis).'");
                            }
                        );
            '
            ]);

        if($model->isInWork()) {
            echo '<div class="loader555" style="float: right; margin-right: 5%"></div>';
        }
        echo '<br><br>';
    }
    ?>
    </div>
    <?php
    /*
    echo $form->field($model, 'norm')
    ->dropDownList(ArrayHelper::map(\frontend\models\Norms::find()
        ->all(), 'id',
        function ($data){return $data->job_code.' > '.$data->job_name;
        }
        ));

        echo Html::a('Добавити', ['set'], ['class' => 'btn btn-primary'])
    */

    //$model->executor = \frontend\models\ExecutorAssignment::findOne(['job_id'=> $model->id])->resident_id;
    ?>
    <?php
    echo \frontend\models\Basic::getNormReport($model);
    ?>
    <br>



</div>

<?= $form->field($model, 'desc')->textarea(['rows' => 3]);?>



<br>
<input type="hidden" name="m" value="<?= Yii::$app->request->get('m') ?>">

<div class="form-group">
    <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
</div>



<?php ActiveForm::end(); ?>

</div>



<?php


$css = <<< CSS

     .loader555 {
        border: 6px solid #F3F3F3; /* Light grey */
        border-top: 6px solid #337ab7; /* Blue */
        border-radius: 50%;
        width: 100px;
        height: 100px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
CSS;
$this->registerCss($css);
?>