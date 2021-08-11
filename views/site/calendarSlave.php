<?php


$year = date('Y');
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

        'startYear'=> $year,
        'minDate'=> new \yii\web\JsExpression('new Date("'.($year-1).'-12-31")'),
        'maxDate'=> new \yii\web\JsExpression('new Date("'.$year.'-12-31")'),
    ],

]);

?>