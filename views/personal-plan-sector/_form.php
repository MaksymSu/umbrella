<!-- on your view layout file HEAD section -->
<!-- on your view layout file HEAD section -->
<script src="tree.js" crossorigin="anonymous"></script>

<?php

\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\tree\TreeView;

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

    $exec = \frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id]);
    if($exec && $exec->parent_job_id)
    $st=true;
    else
    $st=false;




    $inactive_themes_arr = ArrayHelper::map(\frontend\models\Theme::find()
        ->where(['<','deadline', date('Y-m-d')])
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


    echo $form->field($model, 'theme_id')
        ->dropDownList($all_themes_arr, ['options'=>$options_arr, 'disabled'=>$st]);




        echo $form->field($model, 'content')->textarea(['rows' => 3, 'disabled'=>$st]);


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

        echo $form->field($model, 'executor')->dropDownList($arr, [ 'options'=>[$id=>['selected'=>true]]]);

  //  $attr_start = 'started_at';
   // $attr_finish = 'finished_at';
   // $label_start = 'Дата початку по плану';
 //   $label_finish = 'Дата закінчення по плану';
    $label_start = 'Дата початку по плану (по факту: '.Yii::$app->formatter->asDate($model->started_at_fact, 'dd.MM.yyyy').')';
    $label_finish = 'Дата закінчення по плану (по факту: '.Yii::$app->formatter->asDate($model->finished_at_fact, 'dd.MM.yyyy').')';
    echo $form->field($model, 'started_at')->textInput(['type' => 'date', 'style'=>'width: 250px'])
        ->label($label_start,['class'=>'label-class']);;
    echo $form->field($model, 'finished_at')->textInput(['type' => 'date', 'style'=>'width: 250px'])
        ->label($label_finish,['class'=>'label-class']);;


    ?>

    <br>
    <?php
    $dis_lab = true;
        if($assing=$model->getExecutor()) {
            $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
            if ($resident) {
                if ($resident->type)$dis_lab = false;
            }
        }
    echo $form->field($model, 'labor')->textInput(['style'=>'width: 150px', 'disabled' => $dis_lab]);
    ?>




    <?php
    //$exec = \frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id]);
   // if(!$exec || !$exec->parent_job_id) {
        echo \frontend\models\Basic::getNormReport($model);
   // }else{
     //   echo \frontend\models\Basic::getNormReport(\frontend\models\PersonalPlan::findOne($exec->parent_job_id));
  //  }
    ?>

    <?php
    if(Yii::$app->user->can('system')){
        echo '<hr>';
        echo $form->field($model, 'norm_percent')->input('number', ['min' => 0, 'max' => 100, 'style'=> 'max-width:100px',]);
        echo $form->field($model, 'norm_percent_labor')->input('text',['style'=> 'max-width:150px', 'disabled'=>true]);
    }
    ?>

    <?php
    echo '<hr>';
    if($exec && $exec->parent_job_id){
    echo '<p><b>Трудоміскість по плану <font color="#337ab7" size="3">'. $exec->parentJob->labor.'</font> н/г</b></p>';
    echo \frontend\models\Basic::getNormReport(\frontend\models\PersonalPlan::findOne($exec->parent_job_id));
    }
    ?>


    <br>
    <?= $form->field($model, 'status')->dropDownList($model->statuses,
        ['options'=>[3=>['disabled'=>true], 5=>['disabled'=>true]]]); ?>


    <input type="hidden" name="m" value="<?= Yii::$app->request->get('m') ?>">

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>


    <?php
        ActiveForm::end();
    ?>

</div>





<div id="files-table" class="col-sm-6">

<?php
if(!$create) {
    echo '<b>Елементи дерева в роботі:</b><br>'.$model->drawElementsList('<br>') . '<hr>';

    if(Yii::$app->user->can('viewFact')) {
        $dataProvider->query->orFilterWhere(['job_id' => $model->id]);
    }
    require_once('files_table.php');
}
?>

</div>



<?php
$css = <<< CSS

select option[disabled] { font-weight: normal }
select option { font-weight: bold }

CSS;

$this->registerCss($css);


$script = <<< JS

//$(".kv-detail-heading").hide();
//$(".kv-footer-container").hide();

JS;
$this->registerJs($script, yii\web\View::POS_END);