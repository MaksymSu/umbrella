<?php
//load color base to array
$color_json = json_encode(\yii\helpers\ArrayHelper::map(\frontend\models\TimeTable::find()->all(), 'color', function($data){ return 'ЦЕ ПРОСТО ПІДКАЗКА\n\rКод: '.$data->code.'\nЗначення: '.$data->about;}), JSON_UNESCAPED_UNICODE );
echo '<script>var color_arr = JSON.parse(\''.$color_json.'\');</script>';



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
    <p style="text-align: center"><b>Мій календарний графік</b></p>
    <?php
    \frontend\models\Basic::drawTimeTableLegend();
    ?>
    <!--
<div class="form-inline" style="margin-bottom: 10px">


 <span style="margin-right: 10px"><b>Мій календарний графік</b> (редагування)</span>
 <input type="radio" id="vacation" name="drone" value="huey"
        checked >
 <label for="vacation" style="background-color: #ff0; margin-right: 40px; padding: 5px">Відпустка планова</label>

 <input type="radio" id="ill" name="drone" value="dewey">
 <label for="ill" style="background-color: #fbb; margin-right: 40px; padding: 4px">Хвороба</label>

    </div>
-->
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
        'dayContextMenu' => 'function(e) {
             colorRGBstring = $(e.element).css("background-color");
             var msg = color_arr[rgbToHex2(colorRGBstring)];
             if(msg)alert(msg);
             }',],

    /*
    'clientEvents' => [
        'clickDay' => 'function(e) { 
       
       if($(e.element).css("background-color") == "rgb(187, 221, 255)"){
                return;
       }
                
       var e_color = $(e.element).css("background-color");

       if($("#vacation").is(":checked")){
            var act = 1;
            var color = "#ff0";
           
            if(e_color == "rgb(255, 255, 0)"){
                act = 0;
                $(e.element).css("background-color", "#fff");
            }else{
               // alert($(e.element).css("background-color"));
                if(e_color != "rgba(0, 0, 0, 0)" && e_color != "rgb(255, 255, 255)"){
                    return;
                }
                act = 1;
                 $(e.element).css("background-color", color);
            }
        } else
        if($("#ill").is(":checked")){
            var act = 1;
            var color = "#faa";
           
            if(e_color == "rgb(255, 170, 170)"){
                act = 0;
                $(e.element).css("background-color", "#fff");
            }else{
                if(e_color != "rgba(0, 0, 0, 0)" && e_color != "rgb(255, 255, 255)"){
                    return;
                }
                act = 1;
                 $(e.element).css("background-color", color);
            }
        }
        
        $.get( "'.\yii\helpers\Url::toRoute('/site/click').'", {
                        date: e.date.getFullYear().toString() + "-" + (e.date.getMonth()+1).toString() + "-" + e.date.getDate().toString(),//.toISOString().slice(0,10),
                        act: act, 
                        color: color                                
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        ); }',


    ]
    */
]);
?>
    <div>
        <?php //if(Yii::$app->user->can('system')) \frontend\models\Basic::drawCalendarReport2(0, null, 110);?>
    </div>

</div>

<div class="col-sm-6">
    <?php \yii\widgets\Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <div class="form-inline">
        <?php
        if(Yii::$app->user->can('viewFact')) {
            $residents = \frontend\models\Resident::find()->where(['!=', 'div_id', 22])->andWhere(['type'=>0])->orderBy(['sname'=>SORT_ASC])->all();
        }else {
            $residents = \frontend\models\Resident::find()->where(['sector_id' => \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->sector_id])
                ->andWhere(['type'=>0])->all();
        }
        ?>
    <?= $form->field($calendar2, 'resident_id')->dropDownList(\yii\helpers\ArrayHelper
        ::map($residents, 'id',
        function($data){ return $data->sname.' '.$data->fname.' '.$data->lname;})
            //,['prompt' => '- Календарный графік НВК -']
            ,['prompt' => '- Вибір -']

        )?>

    <?= \yii\helpers\Html::submitButton('Показати', ['class' => 'btn btn-primary', 'style'=>'margin-bottom:10px']) ?>
    </div>
    <?php
    echo \tecnocen\yearcalendar\widgets\ActiveCalendar::widget([
        'language' => 'uk',
        'dataProvider' => $dataProvider2,
        'options' => [
            'id' => 'uk-calendar2',
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
            'dayContextMenu' => 'function(e) {
             colorRGBstring = $(e.element).css("background-color");
             var msg = color_arr[rgbToHex2(colorRGBstring)];
             if(msg)alert(msg);
             }',],

    ]);
    ?>

<div>
    <?php if(Yii::$app->user->can('system')) \frontend\models\Basic::drawCalendarReport2(0, null, $calendar2->resident_id);?>
</div>
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

