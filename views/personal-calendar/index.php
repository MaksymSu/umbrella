<?php


//load color base to array
$color_json = json_encode(\yii\helpers\ArrayHelper::map(\frontend\models\TimeTable::find()->all(), 'color', function($data){ return 'ЦЕ ПРОСТО ПІДКАЗКА\n\rКод: '.$data->code.'\nЗначення: '.$data->about;}), JSON_UNESCAPED_UNICODE );
echo '<script>var color_arr = JSON.parse(\''.$color_json.'\');</script>';
//echo '<script>for (var key in color_arr){alert(color_arr[key]);}</script>';


/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
$this->title = 'Мій календарний графік';
$this->params['breadcrumbs'][] = $this->title;
?>

<h3>
    <div id="wwww" ></div>
</h3>

<?php
\Yii::$app->language = 'uk-UK';

use frontend\models\Conference;
use tecnocen\yearcalendar\widgets\ActiveCalendar;
use yii\data\ActiveDataProvider;

$year = date('Y');
?>








<?php
/*
\yii\helpers\Html::dropDownList('id', null,
    \yii\helpers\ArrayHelper::map(\frontend\models\Resident::find()->where(['sector_id' => \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->sector_id])->all(), 'id',
        function($data){ return $data->sname.' '.$data->fname.' '.$data->lname;}), ['class'=>'form-control', 'style'=>'max-width:300px;
            background-color: #fd0', 'prompt'=>'Виберіть співробітника',
        'onchange' =>'
               window.location.href="'.\yii\helpers\Url::toRoute('calendar').'&"+"slave_id="+$(this).val();

            '
    ])
 */
?>



<div id="lb-calendar" class="col-sm-6">


    <?php \yii\widgets\Pjax::begin(); ?>
    <?php $form2 = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <div class="form-inline">
        <?php
        if(Yii::$app->user->can('viewFact') && Yii::$app->user->can('setCalendar')) {
            $residents = \frontend\models\Resident::find()->where(['!=', 'div_id', 22])->andWhere(['type'=>0])->orderBy(['sname'=>SORT_ASC])->all();
        }else {
            if(Yii::$app->user->can('taskForSector')) {
                $residents = \frontend\models\Resident::find()->where(['sector_id' => \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->sector_id])->all();
            }
        }
        ?>

        <?php ////разработка
        if(Yii::$app->user->can('system')){

        } ////
        ?>


        <?= $form2->field($calendar, 'resident_id')->dropDownList(\yii\helpers\ArrayHelper
            ::map($residents, 'id',
                function($data){ return $data->sname.' '.$data->fname.' '.$data->lname;}),
            ['prompt' => '- Вибір -', 'id' => 'calendar-id',
                'onchange'=>'
                
                if($("#calendar2-pattern_id2").val() && $(this).val()){
                $("#pattern-use").attr("disabled", false);
                }else{
                $("#pattern-use").attr("disabled", true);
                }
                '
                ])?>


        <?php

        //echo $form2->field($calendar, 'pattern_id')->textInput();
         echo $form2->field($calendar, 'use')->hiddenInput(['value' => '0'])->label(false);
       // \yii\helpers\Html::input('hidden', 'use',null,['id' => 'use-pattern']);
        ?>

        <?= \yii\helpers\Html::submitButton('Показати', ['class' => 'btn btn-primary', 'style'=>'margin-bottom:10px']) ?>

        <?= \yii\helpers\Html::submitButton('Застосувати шаблон', ['class' => 'btn btn-warning', 'style'=>'margin-bottom:10px; margin-left:40px',
            'onclick' => '$("#calendar2-use").val($("#calendar2-pattern_id2").val());',
            'id'=>'pattern-use',
        ]) ?>
    </div>

    <?php ////разработка
  //  if(Yii::$app->user->can('system')){
        echo '<div class="form-inline"><table><tr><td>';
        echo $form2->field($calendar, 'time_table')->dropDownList(\yii\helpers\ArrayHelper
            ::map(\frontend\models\TimeTable::find()->all(), function($data){return $data->code. '-'.$data->color;},
                function($data){ return '('.$data->code.') ' .$data->about;}), [
            'prompt' => '-Вибрати-',
            'style' => 'width:320px !important',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/personal-calendar/code').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#color-will" ).html( data );
                            }
                        );
                '
        ]);
        echo '&nbsp;<td><div style="margin-bottom: 8px" id="color-will"></div></td>';
        echo '<td style="width: 120px; text-align: right">';
       // echo $form2->field($calendar, 'from_now')
       //     ->checkBox();
       // echo '</td>';
        echo '</tr></table></div>';
  //  } ////
    ?>



    <?php
    echo ActiveCalendar::widget([
        'language' => 'uk',
        'dataProvider' => $dataProvider,
        'options' => [
            'id' => 'uk-calendar',


        ],
        'clientOptions' => [
            'contextMenuItems' => true,
            'style' => 'background',
            'enableRangeSelection' => true,
            'enableContextMenu' => true,

            'startYear'=> $year,
           // 'minDate'=> new \yii\web\JsExpression('new Date("'.($year-1).'-12-31")'),
           // 'maxDate'=> new \yii\web\JsExpression('new Date("'.$year.'-12-31")'),

        ],
        'clientEvents' => [

            //'mouseOnDay' => 'function(e) {alert("cucu"); }',

            'dayContextMenu' => 'function(e) {
             
             colorRGBstring = $(e.element).css("background-color");
             var msg = color_arr[rgbToHex2(colorRGBstring)];
             if(msg)alert(msg);
             }',

            'clickDay' => 'function(e) { 
       
        var act = 2;
        var id = $("#calendar2-time_table").val(); //"#bdf";
        var color = id.split("-")[1];
        
            var colorRGB = hexToRgb(color);
            var htmlRGB = "rgb(" + colorRGB.r + ", " + colorRGB.g + ", " + colorRGB.b + ")";
        
        //alert(color);
        
        
        if($(e.element).css("background-color") == htmlRGB){          //== "rgb(187, 221, 255)"){
            act = 0;
            $(e.element).css("background-color", "#fff");
        }else{
            act = 2;
             $(e.element).css("background-color", color);
        }
       // alert($(e.element).css("background-color"));
        $.get( "'.\yii\helpers\Url::toRoute('/personal-calendar/click').'", {
                        date: e.date.getFullYear().toString() + "-" + (e.date.getMonth()+1).toString() + "-" + e.date.getDate().toString(),//.toISOString().slice(0,10),
                        act: act,
                        color: color,
                        resident_id: $("#calendar-id").val()                         
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        ); }',

        ]
    ]);

    ?>

    <?php ActiveForm::end(); ?>

    <?php

    //\frontend\models\Basic::drawCalendarReport2(1, null, $calendar->resident_id); ?>
    <?php \yii\widgets\Pjax::end(); ?>


</div>

<div class="col-sm-6">
    <?php \yii\widgets\Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <div class="form-inline">
        <?php
        if(Yii::$app->user->can('viewFact')) {
            $residents = \frontend\models\Resident::find()->where(['!=', 'div_id', 22])->all();
        }else {
            $residents = \frontend\models\Resident::find()->where(['sector_id' => \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->sector_id])->all();
        }
        ?>
        <?= $form->field($calendar, 'pattern_id2')->dropDownList(yii\helpers\ArrayHelper
            ::map(\frontend\models\CalendarPatternName::find()->all(), 'id','name'),
            ['prompt' => '- Вибір -',

                'onchange'=>'$("#calendar2-pattern_id").val($(this).val());
                
                if($("#calendar-id").val() && $(this).val()){
                $("#pattern-use").attr("disabled", false);
                }else{
                $("#pattern-use").attr("disabled", true);
                }
                ',



                ])->label('Шаблон')?>

        <?php /*echo $form->field($calendar, 'resident_id')->dropDownList(\yii\helpers\ArrayHelper
            ::map($residents, 'id',
                function($data){ return $data->sname.' '.$data->fname.' '.$data->lname;}),
            ['prompt' => '- Вибір -'])->label('або резидент')
         */
         ?>



        <?= \yii\helpers\Html::submitButton('Показати', ['class' => 'btn btn-primary', 'style'=>'margin-bottom:10px']) ?>

    </div>

    <?php
    echo \tecnocen\yearcalendar\widgets\ActiveCalendar::widget([
        'language' => 'uk',
        'dataProvider' => $dataProvider,
        'options' => [
            'id' => 'uk-calendar2',
        ],
        'clientOptions' => [
            'contextMenuItems' => true,
            'style' => 'background',
            'enableRangeSelection' => true,
            'enableContextMenu' => true,

            'startYear'=> $year,
            'minDate'=> new \yii\web\JsExpression('new Date("'.($year-1).'-12-31")'),
            'maxDate'=> new \yii\web\JsExpression('new Date("'.$year.'-12-31")'),
        ],

        'clientEvents' => [
            'dayContextMenu' => 'function(e) {
             colorRGBstring = $(e.element).css("background-color");
             var msg = color_arr[rgbToHex2(colorRGBstring)];
             if(msg)alert(msg);
             }',],

    ]);
    ?>




    <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>


<?php
$script = <<< JS
//$("#calendar-pattern_id").val('444');
//$(".calendar-header panel panel-default").css("display", "none");

if($("#calendar-id").val() && $("#calendar2-pattern_id").val()){
                $("#pattern-use").attr("disabled", false);
                }else{
                $("#pattern-use").attr("disabled", true);
                }

JS;
$this->registerJs($script, yii\web\View::POS_END);



$script2 = <<< JS
function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  hex = hex.replace(shorthandRegex, function(m, r, g, b) {
          return r + r + g + g + b + b;
      });

  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
        r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
}                
     
function rgbToHex(r, g, b) {
  return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function rgbToHex2(str) {
   
    var arr = str.split(',');
       r = parseInt(arr[0].split('(')[1]);
       g = parseInt(arr[1]);
       b = parseInt(arr[2]);
       return "#" + r.toString(16).charAt(0) + g.toString(16).charAt(0)+ b.toString(16).charAt(0);
}
JS;
$this->registerJs($script2, yii\web\View::POS_END);
