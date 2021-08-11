<h3>
    <div id="wwww" ></div>
</h3>

<?php
\Yii::$app->language = 'uk-UK';
use frontend\models\Conference;
use tecnocen\yearcalendar\widgets\ActiveCalendar;
use yii\data\ActiveDataProvider;
?>


<?php \yii\widgets\Pjax::begin(); ?>
<?php $form = \yii\widgets\ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

<div class="form-inline" id="section-to-hide">
    <?php
    if(Yii::$app->user->can('viewFact')) {
        $residents = \frontend\models\Resident::find()->where(['!=', 'div_id', 22])->all();
    }else {
        $residents = \frontend\models\Resident::find()->where(['sector_id' => \frontend\models\Resident::findOne(['user_id' => Yii::$app->user->id])->sector_id])->all();
    }
    ?>
    <?= $form->field($model, 'resident_id')->dropDownList(\yii\helpers\ArrayHelper
        ::map($residents, 'id',
            function($data){ return $data->sname.' '.$data->fname.' '.$data->lname;})
        //,['prompt' => '- Календарный графік НВК -']
        ,['prompt' => '- Вибір -']

    )?>

    <?= \yii\helpers\Html::submitButton('Показати', ['class' => 'btn btn-primary', 'style'=>'margin-bottom:10px']) ?>
</div>

<?php
$resident = \frontend\models\Resident::findOne($model->resident_id);
if($resident) {
    echo '<div id="section-to-print">';
    echo '<h3>' . $resident->sname .' '.$resident->fname .' '.$resident->lname .' '.'</h3>';
    echo '<h4>'.$resident->div->name.'</h4>';
    //echo '<div style="font-size: larger">';
    echo \frontend\models\Basic::drawCalendarReport2(1, null, $model->resident_id);
    echo '</div>';
    echo '</div>';
    echo '<input type="button" class="btn btn-primary" value="Друкувати" onClick="window.print()">';
}
?>

<?php \yii\widgets\ActiveForm::end(); ?>
<?php \yii\widgets\Pjax::end(); ?>





<?php
$script = <<< JS


JS;
$this->registerJs($script, yii\web\View::POS_END);


$css2 = <<< CSS

@media print {
  body * {
    visibility: hidden;
  }
  #section-to-print, #section-to-print * {
    visibility: visible;
    font-size: 14px;
    padding: 2px;
    text-align: center;
    
  }
  .col-md-2 {
  display: none;
  }
   
   
   #section-to-hide, #section-to-hide * {
    display: none;
  }
@page {
    size: auto;   /* auto is the initial value */
    margin-left: 10px;
    margin-right: 20px;
}
}



CSS;

$this->registerCss($css2);

?>




