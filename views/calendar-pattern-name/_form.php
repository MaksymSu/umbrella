<?php
//load color base to array
$color_json = json_encode(\yii\helpers\ArrayHelper::map(\frontend\models\TimeTable::find()->all(), 'color', function($data){ return 'ЦЕ ПРОСТО ПІДКАЗКА\n\rКод: '.$data->code.'\nЗначення: '.$data->about;}), JSON_UNESCAPED_UNICODE );
echo '<script>var color_arr = JSON.parse(\''.$color_json.'\')</script>';



use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CalendarPatternName */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="wwww" ></div>

<div class="calendar-pattern-name-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'style' => 'min-width: 300px; margin-right: 20px']) ?>

    <?= $form->field($model, 'info')->textArea(['maxlength' => true, 'style' => 'min-width: 300px']) ?>

    <?= $form->field($model, 'year')->dropDownList(\frontend\models\Basic::years(), ['style'=> 'max-width: 200px',
        'onchange'=>'',
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ////разработка
   // if(Yii::$app->user->can('system')){
        echo '<div class="form-inline"><table><tr><td>';
        echo $form->field($model, 'time_table')->dropDownList(\yii\helpers\ArrayHelper
            ::map(\frontend\models\TimeTable::find()->all(), function($data){return $data->code. '-'.$data->color;},
                function($data){ return '('.$data->code.') ' .$data->about;}), [
            'prompt' => '-Вибрати-',
            'style' => 'width:400px !important',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/personal-calendar/code').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#color-will" ).html( data );
                            }
                        );
                '
        ]);
        echo '&nbsp;<td><div style="margin-bottom: 8px" id="color-will"></div></td>';
        echo '</tr></table></div>';
   // } ////
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if(!$create) {

        $year = $model->year;//date('Y');
        echo \tecnocen\yearcalendar\widgets\ActiveCalendar::widget([
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

                'startYear' => $year,
                'minDate' => new \yii\web\JsExpression('new Date("' . ($year - 1) . '-12-31")'),
                'maxDate' => new \yii\web\JsExpression('new Date("' . $year . '-12-31")'),

            ],
            'clientEvents' => [
                'dayContextMenu' => 'function(e) {        
                colorRGBstring = $(e.element).css("background-color");
                var msg = color_arr[rgbToHex2(colorRGBstring)];
                if(msg)alert(msg);
             }',

                'clickDay' => 'function(e) { 
       
                var act = 2;
                var id = $("#calendarpatternname-time_table").val(); //"#bdf";
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
                    $.get( "' . \yii\helpers\Url::toRoute('/calendar-pattern-name/click') . '", {
                                    date: e.date.getFullYear().toString() + "-" + (e.date.getMonth()+1).toString() + "-" + e.date.getDate().toString(),//.toISOString().slice(0,10),
                                    act: act,
                                    color: color,
                                    name_id: "' . $_REQUEST['id'] . '"
                                    
                                   // resident_id: $("#calendar-id").val()                         
                                     } )
                                        .done(function( data ) {
                                            $( "#wwww" ).html( data );
                                        }
                                    ); }',

            ]
        ]);
    }
    ?>


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