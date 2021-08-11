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
<div class="form-block" id="section-to-hide">

<?php $form = \yii\widgets\ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>


    <div class="col-sm-3">

        <?= $form->field($model, 'year')->dropDownList(\frontend\models\Basic::years(), ['style'=> 'max-width: 200px',
            'onchange'=>'',
            ]);
        ?>
    </div>

    <div class="col-lg-3">

        <?= $form->field($model, 'month')->dropDownList(\frontend\models\Basic::$m_arr2, ['style'=> 'max-width: 200px',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/site/setdays').'", { month: $(this).val(), 
                        year: $( "#'.\yii\helpers\Html::getInputId($model, 'year').'" ).val(),
                        day: '.$model->day_left.'
                          } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'day_left').'" ).html( data );
                            }
                        );',
            ]);
        ?>
    </div>

    <div class="col-lg-3">

        <?= $form->field($model, 'day_left')->dropDownList(\frontend\models\Basic::getDaysInMonth($model->year, $model->month), ['style'=> 'max-width: 200px',
            'prompt' => '- Кінець місяця -',

        ]);
        ?>
    </div>

    <div class="col-lg-3">

        <?= $form->field($model, 'IPN')->textInput(['maxlength' => true]) ?>

    </div>




    <div class="col-lg-3">
        <?php
        $structs = \frontend\models\Struct::find()->all();
        ?>
        <?= $form->field($model, 'struct_id')->dropDownList(\yii\helpers\ArrayHelper
            ::map($structs, 'id', 'name')
            ,['prompt' => '- Всі -',
                'onchange'=>'
                        $( "#'.\yii\helpers\Html::getInputId($model, 'sector_id').'" ).empty().append("<option>- Всі -</option>");
                        $( "#'.\yii\helpers\Html::getInputId($model, 'resident_id').'" ).empty().append("<option>- Всі -</option>");
                        
                        $.get( "'.\yii\helpers\Url::toRoute('/site/set-struct').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'div_id').'" ).html( data );
                            }
                        );',
            ]
        )?>
    </div>



    <div class="col-lg-3">
        <?php
        $divs = \frontend\models\Div::findAll(['struct_id'=>$model->struct_id]);
        ?>
    <?= $form->field($model, 'div_id')->dropDownList(\yii\helpers\ArrayHelper::map($divs, 'id', 'name'),
        ['prompt' => '- Всі -',
        'onchange'=>'
                        $( "#'.\yii\helpers\Html::getInputId($model, 'resident_id').'" ).empty().append("<option>- Всі -</option>");
                        
                        $.get( "'.\yii\helpers\Url::toRoute('/site/set-div').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'resident_id').'" ).html( data )
                            }
                        );',
    ])?>
    </div>


    <div class="col-lg-3">
        <?php
       /* $sectors = \frontend\models\Sectors::findAll(['div_id'=>$model->div_id]);

        echo $form->field($model, 'sector_id')->dropDownList(\yii\helpers\ArrayHelper::map($sectors, 'id', 'name'),
            ['prompt' => '- Всі -',
            'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/site/set-sector').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#'.\yii\helpers\Html::getInputId($model, 'resident_id').'" ).html( data );
                            }
                        );',
        ]
    );*/


       // if(Yii::$app->user->can('system')) {
            $residents = \frontend\models\Resident::findAll(['div_id' => $model->div_id, 'type' => 0]);
            echo $form->field($model, 'resident_id')->dropDownList(\yii\helpers\ArrayHelper
                ::map($residents, 'id',
                    function ($data) {
                        return $data->sname . ' ' . $data->fname . ' ' . $data->lname;
                    }),
                [
                        //'prompt' => '- Всі -',

                        'multiple'=>'multiple',
                    //    'class'=>'chosen-select input-md required',
                    'style' => 'height: 150px',

                ])->label('Резіденти');
      //  }

    echo '</div>';





       // if(Yii::$app->user->can('system')) {

            echo '<div class="col-lg-3">';

            echo $form->field($model, 'type_selected')->dropDownList($model->types);

            echo '</div>';

        //}


    ?>

    <?= \yii\helpers\Html::submitButton('Зформувати', ['class' => 'btn btn-primary', 'style'=>'margin-bottom:10px', 'onclick'=>'
    $("#spinner").css("display", "block");
    ']) ?>
</div>


    <div class="cssload-thecube" id="spinner" style="display: none">
        <div class="cssload-cube cssload-c1"></div>
        <div class="cssload-cube cssload-c2"></div>
        <div class="cssload-cube cssload-c4"></div>
        <div class="cssload-cube cssload-c3"></div>
    </div>


<?php
//$resident = \frontend\models\Resident::findOne($model->resident_id);
echo '<div id="section-to-print">';
?>
    <div style="text-align: left; font-weight: 600; max-width: 300px">
        <?php
        \frontend\models\Basic::drawHeadLeftTable($model);
        ?>
    </div>

    <div style="text-align: center; font-weight: 600">
        <?php
        \frontend\models\Basic::drawHeadCenterTable($model);
        ?>
    </div>

    <?php
    //if(Yii::$app->user->can('system')){
    echo '<span style="float: right; text-align: right; font-size: xx-small">Сформовано '.date("d-m-Y G:i").'</span>';
    //}
    ?>

<?php
\frontend\models\Basic::drawTable(
    $model->year,
    $model->month,
    $model->struct_id,
    $model->div_id,
    false,
    $model->resident_id,
    $model->day_left,
    $model->type_selected
);
/*
if($model->resident_id > 0) {
    echo '<h3>' . $resident->sname .' '.$resident->fname .' '.$resident->lname .' '.'</h3>';
    echo '<h4>'.$resident->struct->name.', '.$resident->div->name.', '.$resident->sector->name.'</h4>';
    echo \frontend\models\Basic::drawCalendarReportResident(1,  $model->resident_id);

}
elseif($model->sector_id >0) {
    $sector = \frontend\models\Sectors::findOne($model->sector_id);
    echo '<h4>'.$sector->name.'</h4>';
    echo \frontend\models\Basic::drawCalendarReportSector(1,  $model->sector_id);

}
elseif($model->div_id >0) {
    $div = \frontend\models\Div::findOne($model->div_id);
    echo '<h4>'.$div->name.'</h4>';
    echo \frontend\models\Basic::drawCalendarReportDiv(1,  $model->div_id);
}
elseif($model->struct_id >0) {
    $struct = \frontend\models\Struct::findOne($model->struct_id);
    echo '<h4>'.$struct->name.'</h4>';
    echo \frontend\models\Basic::drawCalendarReportStruct(1,  $model->struct_id);
}




*/


?>
    <div style="text-align: left;">
        <?php
        \frontend\models\Basic::drawFootTable($model->div_id, $model->type_selected);
        ?>
    </div>
</div>
    <?php
if($model->struct_id >0 || $model->div_id>0 || $model->sector_id>0 || $model->resident_id>0)
echo '<input type="button" class="btn btn-primary" value="Друкувати" onClick="window.print()">';

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

    .table > thead > tr > th, 
    .table > tbody > tr > th, 
    .table > tfoot > tr > th, 
    .table > thead > tr > td, 
    .table > tbody > tr > td, 
    .table > tfoot > tr > td {
    border: 1px solid #000 !important;
    }

@page {
    size: auto;   /* auto is the initial value */
    margin-left: 10px;
    margin-right: 20px;
}
}


.cssload-thecube {
	width: 73px;
	height: 73px;
	margin: 0 auto;
	margin-top: 49px;
	position: relative;
	transform: rotateZ(45deg);
		-o-transform: rotateZ(45deg);
		-ms-transform: rotateZ(45deg);
		-webkit-transform: rotateZ(45deg);
		-moz-transform: rotateZ(45deg);
}
.cssload-thecube .cssload-cube {
	position: relative;
	transform: rotateZ(45deg);
		-o-transform: rotateZ(45deg);
		-ms-transform: rotateZ(45deg);
		-webkit-transform: rotateZ(45deg);
		-moz-transform: rotateZ(45deg);
}
.cssload-thecube .cssload-cube {
	float: left;
	width: 50%;
	height: 50%;
	position: relative;
	transform: scale(1.1);
		-o-transform: scale(1.1);
		-ms-transform: scale(1.1);
		-webkit-transform: scale(1.1);
		-moz-transform: scale(1.1);
}
.cssload-thecube .cssload-cube:before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgb(0,135,184);
	animation: cssload-fold-thecube 2.76s infinite linear both;
		-o-animation: cssload-fold-thecube 2.76s infinite linear both;
		-ms-animation: cssload-fold-thecube 2.76s infinite linear both;
		-webkit-animation: cssload-fold-thecube 2.76s infinite linear both;
		-moz-animation: cssload-fold-thecube 2.76s infinite linear both;
	transform-origin: 100% 100%;
		-o-transform-origin: 100% 100%;
		-ms-transform-origin: 100% 100%;
		-webkit-transform-origin: 100% 100%;
		-moz-transform-origin: 100% 100%;
}
.cssload-thecube .cssload-c2 {
	transform: scale(1.1) rotateZ(90deg);
		-o-transform: scale(1.1) rotateZ(90deg);
		-ms-transform: scale(1.1) rotateZ(90deg);
		-webkit-transform: scale(1.1) rotateZ(90deg);
		-moz-transform: scale(1.1) rotateZ(90deg);
}
.cssload-thecube .cssload-c3 {
	transform: scale(1.1) rotateZ(180deg);
		-o-transform: scale(1.1) rotateZ(180deg);
		-ms-transform: scale(1.1) rotateZ(180deg);
		-webkit-transform: scale(1.1) rotateZ(180deg);
		-moz-transform: scale(1.1) rotateZ(180deg);
}
.cssload-thecube .cssload-c4 {
	transform: scale(1.1) rotateZ(270deg);
		-o-transform: scale(1.1) rotateZ(270deg);
		-ms-transform: scale(1.1) rotateZ(270deg);
		-webkit-transform: scale(1.1) rotateZ(270deg);
		-moz-transform: scale(1.1) rotateZ(270deg);
}
.cssload-thecube .cssload-c2:before {
	animation-delay: 0.35s;
		-o-animation-delay: 0.35s;
		-ms-animation-delay: 0.35s;
		-webkit-animation-delay: 0.35s;
		-moz-animation-delay: 0.35s;
}
.cssload-thecube .cssload-c3:before {
	animation-delay: 0.69s;
		-o-animation-delay: 0.69s;
		-ms-animation-delay: 0.69s;
		-webkit-animation-delay: 0.69s;
		-moz-animation-delay: 0.69s;
}
.cssload-thecube .cssload-c4:before {
	animation-delay: 1.04s;
		-o-animation-delay: 1.04s;
		-ms-animation-delay: 1.04s;
		-webkit-animation-delay: 1.04s;
		-moz-animation-delay: 1.04s;
}



@keyframes cssload-fold-thecube {
	0%, 10% {
		transform: perspective(136px) rotateX(-180deg);
		opacity: 0;
	}
	25%,
				75% {
		transform: perspective(136px) rotateX(0deg);
		opacity: 1;
	}
	90%,
				100% {
		transform: perspective(136px) rotateY(180deg);
		opacity: 0;
	}
}

@-o-keyframes cssload-fold-thecube {
	0%, 10% {
		-o-transform: perspective(136px) rotateX(-180deg);
		opacity: 0;
	}
	25%,
				75% {
		-o-transform: perspective(136px) rotateX(0deg);
		opacity: 1;
	}
	90%,
				100% {
		-o-transform: perspective(136px) rotateY(180deg);
		opacity: 0;
	}
}

@-ms-keyframes cssload-fold-thecube {
	0%, 10% {
		-ms-transform: perspective(136px) rotateX(-180deg);
		opacity: 0;
	}
	25%,
				75% {
		-ms-transform: perspective(136px) rotateX(0deg);
		opacity: 1;
	}
	90%,
				100% {
		-ms-transform: perspective(136px) rotateY(180deg);
		opacity: 0;
	}
}

@-webkit-keyframes cssload-fold-thecube {
	0%, 10% {
		-webkit-transform: perspective(136px) rotateX(-180deg);
		opacity: 0;
	}
	25%,
				75% {
		-webkit-transform: perspective(136px) rotateX(0deg);
		opacity: 1;
	}
	90%,
				100% {
		-webkit-transform: perspective(136px) rotateY(180deg);
		opacity: 0;
	}
}

@-moz-keyframes cssload-fold-thecube {
	0%, 10% {
		-moz-transform: perspective(136px) rotateX(-180deg);
		opacity: 0;
	}
	25%,
				75% {
		-moz-transform: perspective(136px) rotateX(0deg);
		opacity: 1;
	}
	90%,
				100% {
		-moz-transform: perspective(136px) rotateY(180deg);
		opacity: 0;
	}
}


CSS;

$this->registerCss($css2);

?>




