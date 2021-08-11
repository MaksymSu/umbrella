<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>



<?php $form = ActiveForm::begin(); ?>

<div class="col-lg-3">
<?= $form->field($model, 'month_id')->dropDownList($model->m_arr,

    [
        'prompt' => '- Виберіть -',
        'onchange'=>'
                        $.get( "'.\yii\helpers\Url::toRoute('/workers/set-month').'", { id: $(this).val() } )
                            .done(function( data ) {
                                $( "#report" ).html( data );
                            }
                        );',
    ]

    ); ?>
</div>
<?php ActiveForm::end(); ?>


<div id="report" class="col-sm-12">

</div>




