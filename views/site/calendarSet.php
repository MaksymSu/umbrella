<h3>
    <div id="wwww" ></div>
</h3>

<?php
\Yii::$app->language = 'uk-UK';
use frontend\models\Conference;
use tecnocen\yearcalendar\widgets\ActiveCalendar;
use yii\data\ActiveDataProvider;




$m = '-'.str_pad(1, 2, "0", STR_PAD_LEFT).'-';
echo '<div id="section-to-print">';
echo '<h2>'.\frontend\models\Basic::drawCalendarReport2(1).'</h2>';
echo '</div>';

/*
\yii\widgets\Pjax::begin();

 //echo $dataProvider->count;exit();


$year = date('Y');
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
        'minDate'=> new \yii\web\JsExpression('new Date("'.($year-1).'-12-31")'),
        'maxDate'=> new \yii\web\JsExpression('new Date("'.$year.'-12-31")'),

    ],
    'clientEvents' => [
        'clickDay' => 'function(e) { 
       
        var act = 2;
        var color = "#bdf";
        
        if($(e.element).css("background-color") == "rgb(187, 221, 255)"){
            act = 0;
            $(e.element).css("background-color", "#fff");
        }else{
            act = 2;
             $(e.element).css("background-color", color);
        }
       // alert($(e.element).css("background-color"));
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
]);

\yii\widgets\Pjax::end();
?>

*/
?>




<?php
$script = <<< JS
function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var mywindow = window.open('', 'my div', 'height=1400,width=1600');
        mywindow.document.write('<html><head>');
        mywindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }


    function printDiv() 
{

  var divToPrint=document.getElementById('section-to-print');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}

function myPrint() {
document.querySelector("#mydiv").addEventListener("click", function() {
	window.print();
});
}


document.querySelector("#print").addEventListener("click", function() {
	window.print();
});

JS;
$this->registerJs($script, yii\web\View::POS_END);


$css2 = <<< CSS

@media print {
  body * {
    visibility: hidden;
  }
  #section-to-print, #section-to-print * {
    visibility: visible;
  }
  .col-md-2 {
  display: none;
  }

@page {
    size: auto;   /* auto is the initial value */
}
}



CSS;

$this->registerCss($css2);

?>

<input type="button" class="btn btn-primary" value="Друкувати" onClick="window.print()">

