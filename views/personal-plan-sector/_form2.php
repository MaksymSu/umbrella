<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="personal-plan-form col-sm-6">

    <?php $form = ActiveForm::begin(); ?>



    <?php

    /*
    $good_themes_arr = ArrayHelper::map(\frontend\models\Theme::find()
        ->where(['>','deadline', date('Y-m-d')])
        ->all(),
        'id',function ($data){return $data->number.' - '.$data->content;});

    if(array_key_exists($model->theme_id, $good_themes_arr)){
        $opt = true;
    }else{
        $opt = false;
    }

    echo $form->field($model, 'theme_id')
    ->dropDownList($good_themes_arr, ['disabled' => $opt]);
*/



/*

    $inactive_themes_arr = ArrayHelper::map(\frontend\models\Theme::find()
        ->where(['<=','deadline', date('Y-m-d')])
        ->all(),
        'id',function ($data){return $data->number.' - '.$data->content;});

    $all_themes_arr = ArrayHelper::map(\frontend\models\Theme::find()
        ->all(),
        'id',function ($data){return $data->number.' - '.$data->content;});

    $options_arr = [];
    $options_arr2 = [2=>['disabled'=>true]];

    foreach ($inactive_themes_arr as $id=>$theme){
        $options_arr[$id] = ['disabled'=>true];
    }

    if(array_key_exists($model->theme_id, $inactive_themes_arr)){
        $opt = false;
    }else{
        $opt = true;
    }
*/

    $job_exec_divs = \frontend\models\Planning\ExecDivAssignment
        ::find()->select('job_id')->where(['div_id'=>\frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->div->id]);
    //echo '<h3>'.$job_exec_divs->count().'</h3>';


    $themes = \frontend\models\Planning\PersonalPlan::find()->where(['in','id',$job_exec_divs]);
    $themes->andFilterWhere(['in', 'theme_id', \frontend\models\Theme::find()->select('id')->where(['>=','deadline', date('Y-m-d')])]);
    //echo '<h3>'.$themes->count().'</h3>';

    $all_themes_arr = ArrayHelper::map($themes->all(),
        'theme_id',function ($data){return $data->theme->number.' - '.$data->theme->content;});

    echo $form->field($model, 'theme_id')->dropDownList($all_themes_arr,
        ['prompt' => '- Виберіть тему -',
                'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('set-theme').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'idid').'" ).html( data );
                            }
                        );',
        ]
        );







    $jobs = \frontend\models\PersonalPlan::find()->where(['theme_id' => $model->theme_id])->andFilterWhere(['!=', 'resident_id', null]);
   // echo '<h3>'.$jobs->count().'</h3>';
    echo $form->field($model, 'idid')->dropDownList(ArrayHelper::map($jobs->all(), 'id', 'content'),

        ['onchange'=>'
                        jQuery.ajaxSetup({async:false});
                        
                        $.get( "'.\yii\helpers\Url::toRoute('set-job').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).val( data );
                            }
                        );
                        jQuery.ajaxSetup({async:true});
                        
                        
                        $.get( "'.\yii\helpers\Url::toRoute('set-percent').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'percent').'" ).val( data );
                                $( "#'.\yii\helpers\Html::getInputId($model, 'percent').'" ).attr("max", data);
                            }
                        );
                        
                      
                        $.get( "'.\yii\helpers\Url::toRoute('set-progress').'", { 
                                         id: $( "#'.\yii\helpers\Html::getInputId($model, 'executor').'" ).val(),
                                         percent: $( "#'.\yii\helpers\Html::getInputId($model, 'percent').'" ).val(),
                                         labor: $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).val(),
                                         started_at: $( "#'.\yii\helpers\Html::getInputId($model, 'started_at').'" ).val(),
                                         finished_at: $( "#'.\yii\helpers\Html::getInputId($model, 'finished_at').'" ).val()
                                         } )
                                                        .done(function( data ) {
                                $("#progress").html(data);
                            }
                            );    
                        ',
        ]
        );
        echo $form->field($model, 'content')->textarea(['rows' => 3, 'style'=>'display:none', 'value' => 'temp'])->label('',['style'=>'display:none']);



        $arr = ArrayHelper::map(\frontend\models\Resident::find()
            ->where(['sector_id' => $model->getSector()->sector_id])
            ->all(),
            'id', function ($data) {
                return $data->sname . ' ' . $data->fname . ' ' . $data->lname;
            });

        //удалим из списка себя(нач. сектора)
        // unset($arr[\frontend\models\Resident::findOne(['user_id'=>Yii::$app->user->id])->id]);
        if($executor = $model->getExecutor())
        {
            $id = $executor->resident_id;
        }else{
            $id = null;
        }

        echo $form->field($model, 'executor')->dropDownList($arr, [ 'options'=>[$id=>['selected'=>true]],
            'prompt'=>'- Виберіть -',
            'onchange'=>'
            $.get( "'.\yii\helpers\Url::toRoute('set-progress').'", { 
             id: $(this).val(),
             percent: $( "#'.\yii\helpers\Html::getInputId($model, 'percent').'" ).val(),
             labor: $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).val(),
             started_at: $( "#'.\yii\helpers\Html::getInputId($model, 'started_at').'" ).val(),
             finished_at: $( "#'.\yii\helpers\Html::getInputId($model, 'finished_at').'" ).val()
             } )
                            .done(function( data ) {
    $("#progress").html(data);
    ;
    if($( "#'.\yii\helpers\Html::getInputId($model, 'executor').'" ).val() > 0) {$("#form2-submit").css("display", "block");}
    else {$("#form2-submit").css("display", "none");}
}
);           
            '

            ]);

  //  $attr_start = 'started_at';
   // $attr_finish = 'finished_at';
   // $label_start = 'Дата початку по плану';
 //   $label_finish = 'Дата закінчення по плану';

    $label_start = 'Дата початку по плану (по факту: '.Yii::$app->formatter->asDate($model->started_at_fact, 'dd.MM.yyyy').')';
    $label_finish = 'Дата закінчення по плану (по факту: '.Yii::$app->formatter->asDate($model->finished_at_fact, 'dd.MM.yyyy').')';
    echo $form->field($model, 'started_at')->textInput(['type' => 'date', 'style'=>'width: 250px'])
        ->label($label_start,['class'=>'label-class']);;
    echo $form->field($model, 'finished_at')->textInput(['type' => 'date', 'style'=>'width: 250px',

        'onchange'=>'
            $.get( "'.\yii\helpers\Url::toRoute('set-progress').'", { 
             id: $( "#'.\yii\helpers\Html::getInputId($model, 'executor').'" ).val(),
             percent: $( "#'.\yii\helpers\Html::getInputId($model, 'percent').'" ).val(),
             labor: $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).val(),
             started_at: $( "#'.\yii\helpers\Html::getInputId($model, 'started_at').'" ).val(),
             finished_at: $(this).val()
             } )
                            .done(function( data ) {
    $("#progress").html(data);
        if($( "#'.\yii\helpers\Html::getInputId($model, 'executor').'" ).val() > 0) {$("#form2-submit").css("display", "block");}
    else {$("#form2-submit").css("display", "none");}
}
);           
            '


    ])
        ->label($label_finish,['class'=>'label-class']);;

/*
    echo '<label for="personalplan-started_at" >'.$label_start.'</label>';
    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'started_at',
        'template' => '{addon}{input}',
        'language' => 'uk',
     //   'form' => $form,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',

        ]
    ]);

    echo '<br>
        <label for="personalplan-started_at" >'.$label_finish.'</label>';
    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'finished_at',
        'template' => '{addon}{input}',
        'language' => 'uk',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',

        ]
    ]);
*/
    ?>

    <br>
    <div class="col-sm-3">
    <?= $form->field($model, 'labor')->textInput(['style'=>'width: 150px', 'disabled' => true,

        'onchange'=>'
            $.get( "'.\yii\helpers\Url::toRoute('set-progress').'", { 
             id: $( "#'.\yii\helpers\Html::getInputId($model, 'idid').'" ).val(),
             percent: $(this).val()
             } )
                            .done(function( data ) {
                                $("#progress").html(data);
                            }
                        );
            '

        ]);?>
    </div>
    <?= $form->field($model, 'percent')->input('number', ['min' => 0, 'max' => 100, 'style'=> 'max-width:100px',
        'onchange'=>'
            $.get( "'.\yii\helpers\Url::toRoute('set-part').'", { 
             id: $( "#'.\yii\helpers\Html::getInputId($model, 'idid').'" ).val(),
             percent: $(this).val()
             } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).val( data );
                            }
                        );
             $.get( "'.\yii\helpers\Url::toRoute('set-progress').'", { 
             id: $( "#'.\yii\helpers\Html::getInputId($model, 'executor').'" ).val(),
             percent: $(this).val(),
             labor: $( "#'.\yii\helpers\Html::getInputId($model, 'labor').'" ).val(),
             started_at: $( "#'.\yii\helpers\Html::getInputId($model, 'started_at').'" ).val(),
             finished_at: $( "#'.\yii\helpers\Html::getInputId($model, 'finished_at').'" ).val()
             } )
                            .done(function( data ) {
                                $("#progress").html(data);
                            }
                        );           
            '
        ]) ?>

    <div class="col-sm-5" id="progress">
        <?php
       // $m = date('m');
       // echo \frontend\models\Basic::getZagruskaHtml($model->resident_id, $m);
        ?>
    </div>

<br><br><br>
        <?php
  //  echo \frontend\models\Basic::getNormReport(\frontend\models\PersonalPlan::findOne($model->idid));
    ?>
    <br>
    <?= $form->field($model, 'status')->dropDownList($model->statuses,
        ['options'=>[3=>['disabled'=>true], 5=>['disabled'=>true]]]); ?>


    <input type="hidden" name="m" value="<?= Yii::$app->request->get('m') ?>">


    <?php
    if($model->resident_id > 0){
        $dis = "block";
    }else {
        $dis = "none";
    }
    ?>
    <div class="form-group"  id="form2-submit" style="display: <?= $dis?>">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


    <div id="files-table" class="col-sm-6">

        <?php
        if(!$create) {
            if(Yii::$app->user->can('viewFact')) {
                $dataProvider->query->orFilterWhere(['job_id' => $model->id]);
            }
            require_once('files_table.php');
        }
        ?>

    </div>




<?php
$script = <<< JS

JS;
$this->registerJs($script, yii\web\View::POS_END);