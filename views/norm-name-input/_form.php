<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormNameInput */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="norm-name-input-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 3]) ?>


    <?= $form->field($model, 'unit_id')->dropDownList(\yii\helpers\ArrayHelper::map(\frontend\models\NormUnit::find()->all(),
        'id', 'content')); ?>


    <?= $form->field($model, 'variants')->textarea(['rows' => 5, 'style'=>'width: 500px']) ?>

    <?php
   // if(Yii::$app->user->can('system')){
        echo '<input type="text" id="kk" value="1" class="form-control col-lg-3" name="enter" style="width: 100px">';
        echo '<input type="button" id="kkuse" class="btn btn-primary" value="Застосувати як коэфф"> ';
        echo '<input type="button" id="kkno" class="btn btn-warning" value="Відміна">';
        echo '<br><br>';


        echo '
        <script>
        var arr;
          kkuse.onclick = function() {
            a = document.getElementById("kk").value;
            res = String();
            arr = document.getElementById("normnameinput-variants").textContent;
                    novelties = arr.split("\n");
                    for(i=0; i<5; i++){
                        difficulties = novelties[i].split(" ");
                        for(j=0; j<6; j++){
                            res = res + (difficulties[j]*a).toFixed(2);
                            if(j < 5)res += " ";
                        }
                        if(i < 4)res += "\n";
                    }
                    
                document.getElementById("normnameinput-variants").textContent = res;
          };
          
          kkno.onclick = function() {
             document.getElementById("normnameinput-variants").textContent = arr;
          }
        </script>
        ';
  //  }
    ?>


    <?= $form->field($model, 'status')->dropDownList($model->statuses) ?>


    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
