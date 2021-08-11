<script src="tree.js" crossorigin="anonymous"></script>
<script defer src="all.js"></script>
<script defer src="v4-shims.js"></script>


<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
//if(Yii::$app->user->can('system')){

    echo '<div class="col-sm-4">';
//}
?>
<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'content',
        [                      // the owner name of the model
            'label' => 'Тема',
            'value' => function ($model){
                if($model->theme)return $model->theme->content;
                return '';
            },
        ],
        [                      // the owner name of the model
            'label' => 'Номер теми',
            'value' => function ($model){
                if($model->theme)return $model->theme->number;
                return '';
            },
        ]
    ],
])
?>
<div class="personal-plan-form">
    <?php $form = ActiveForm::begin(); ?>


    <?php
    $label_start = 'Дата початку по факту (по плану: '.Yii::$app->formatter->asDate($model->started_at, 'dd.MM.yyyy').')';
    $label_finish = 'Дата закінчення по факту (по плану: '.Yii::$app->formatter->asDate($model->finished_at, 'dd.MM.yyyy').')';

    echo $form->field($model, 'started_at_fact')->textInput(['type' => 'date', 'style'=>'width: 250px'])
        ->label($label_start,['class'=>'label-class']);

    echo $form->field($model, 'finished_at_fact')->textInput(['type' => 'date', 'style'=>'width: 250px'])
        ->label($label_finish,['class'=>'label-class']);

?>

    <?php
    $dis_lab = true;
    if(!empty($model->theme->no_norms)){
                $dis_lab = false;
    }
    ?>


 <?= $form->field($model, 'labor')->textInput(['maxlength' => true, 'style'=>'width: 250px', 'disabled' => $dis_lab]); ?>
 <?php
 $exec = \frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id]);

/*
 if(Yii::$app->user->can('system')){
     echo Html::a('Калькулятор трудоміскості', ['norm-name/index', 'id' => $model->id, 'm'=>$m], ['class' => 'btn btn-primary']);
     echo '<br><br>';
 }
 else
*/
 if(Yii::$app->user->can('updateTerms')){//} && !$exec->parent_job_id) {
     echo Html::a('Калькулятор трудоміскості', ['norm-name/index', 'id' => $model->id, 'm'=>$m], ['class' => 'btn btn-primary']);
     echo '<br><br>';
    // echo \frontend\models\Basic::getNormReport($model);
 }

 ?>

    <?php
   // if(!$exec->parent_job_id) {
        echo $fact = \frontend\models\Basic::getNormReport($model);

        if(strpos($fact,'не застосовано') && Yii::$app->user->can('system')){
            echo '<script>
                    window.onload = function() {
                    document.getElementsByClassName("btn-success")[0].disabled=true;
                    }
                    </script>';
        }else{
            echo '<script>
                    window.onload = function() {
                    document.getElementsByClassName("btn-success")[0].disabled=false;
                    }
                    </script>';
        }
  //  }else{
   //     echo \frontend\models\Basic::getNormReport(\frontend\models\PersonalPlan::findOne($exec->parent_job_id));
    //}

/*
    echo '<hr>';
    if($exec->parent_job_id){
        echo '<p><b>Трудоміскість по плану <font color="#337ab7" size="3">'. $exec->parentJob->labor.'</font> н/г</b></p>';
        echo \frontend\models\Basic::getNormReport(\frontend\models\PersonalPlan::findOne($exec->parent_job_id));
    }
*/
    ?>
    <br>



    </div>

    <?= $form->field($model, 'desc')->textarea(['rows' => 3]);?>

    <?= $form->field($model, 'status')->dropDownList($model->statuses,
        ['options'=>[1=>['disabled'=>true], 2=>['disabled'=>true], 4=>['disabled'=>true]]]); ?>

    <br>
    <input type="hidden" name="m" value="<?= Yii::$app->request->get('m') ?>">

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

<?php
//if(!Yii::$app->user->can('system')) {
 //   ActiveForm::end();
//}
?>

</div>


<?php
//if(Yii::$app->user->can('system')){

    echo '</div>';
//echo 'theme_id: '.$model->theme_id;
    echo '<div class="col-sm-8">';

    if($model->theme->number == '700'){
        $query_query = \frontend\models\Tree::find()->addOrderBy('root, lft');
    }else{
        $query_query = \frontend\models\Tree::find()->addOrderBy('root, lft')
            ->where(['theme_id' => $model->theme_id]);
    }
    //echo '<b>Елементи дерева в роботі</b>';
    echo $form->field($model, 'nodes')->widget(\kartik\tree\TreeViewInput::classname(),[
        'name' => 'kvTreeInput',
        'value' => 'true', // preselected values
        'query' => $query_query,//\frontend\models\Tree::find()->addOrderBy('root, lft')->where(['theme_id' => $model->theme_id]),
        'headingOptions' => ['label' => \frontend\models\Themes::findOne($model->theme_id)->number],
        'rootOptions' => ['label'=>'<i class="fas fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => true,
        'cascadeSelectChildren' => false,
        'autoCloseOnSelect' => false,
        'defaultChildNodeIcon' => '<i class="fa fa-cog"></i>',

        'options' => ['disabled' => false, 'onchange' => '
        
        $.get( "'.\yii\helpers\Url::toRoute('set-files-table').'", { nodes: $(this).val(), job_id: '.$model->id.' } )
                            .done(function( data ) {
                                $( "#files-table" ).html( data );
                            }
                        );
                        
        $.get( "'.\yii\helpers\Url::toRoute('set-elements-dropdown').'", { nodes: $(this).val() } )
                            .done(function( data ) {
                                $( "#personalplan-cur_nodes" ).html( data );
                            }
                        );
        
        '],

    ]);

/*
    echo $form->field($model, 'attachment_1[]')->widget(\kartik\file\FileInput::classname(), [
        'options'=>[
                'accept'=>'image/*',
                'multiple' => true,
        ],
        'language'=> 'uk',
        'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png'],
            'showUpload' => false,
            'showCaption' => false,
            // 'language'=> "uk-UA",
            //'browseLabel' => 'Вибрати файл',
            // 'removeLabel' => 'Видалити',
        ]
    ]);
*/
echo '<h2>';
   //echo  \yii\helpers\Url::to(['/personal-plan/upload']);
echo '</h2>';

$nodes = \frontend\models\Tree::find()->where(['in','id', explode(',', $model->nodes)])->all();

echo '<hr>';
    echo '<div class="form-inline"><b>Додати файл до елементу </b> ';
    echo Html::activeDropDownList($model,
        'cur_nodes',
        // explode(',', $model->nodes),
        \yii\helpers\ArrayHelper
            ::map($nodes, 'id',
                function ($data) {
                    return $data->name.' ('.$data->N.')';
                }),

        ['class' => 'form-control',
        ]);

    echo '<nobr><span style="margin-left: 3%"><b>П.З.розробки: </b></span>';
    echo Html::activeDropDownList($model,
        'cur_system',
        // explode(',', $model->nodes),
        \yii\helpers\ArrayHelper
            ::map(\frontend\models\CdSystems::find()->all(), 'id', 'name'),

        ['class' => 'form-control',
        ]);

    echo '</nobr></div>';


    echo FileInput::widget([
        //'model' => \frontend\models\PersonalPlan::findOne($job_id),
        'name' => 'attachment_1[]',
        //'attribute' => 'attachment_1[]',
        'options' => ['multiple' => false],
        'language'=> 'uk',
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => true,
            'uploadUrl' => '/umbrella/frontend/web/index.php?r=personal-plan/upload',///\yii\helpers\Url::to(['/personal-plan/upload']),
            //'uploadAsync' => true,

            'uploadExtraData' => new \yii\web\JsExpression('function (previewId, index) {
                    return {
                        node_id: $("#personalplan-cur_nodes").val(), 
                        theme_id: '.$model->theme_id.',
                        job_id: '.$model->id.',
                        system_id: $("#personalplan-cur_system").val(),
                    };
                }'),


        ],


         'pluginEvents' => [
           'fileuploaded' => 'function( data ) {
                               $.get( "'.\yii\helpers\Url::toRoute('set-files-table').'", { 
                               nodes: $("#personalplan-cur_nodes option").map(function() { return this.value;}).get().join(","),
                                job_id: '.$model->id.', } )
                            .done(function( data ) {
                                $( "#files-table" ).html( data );
                            }
                        );
                            }'
         ],

    ]);






/*
    echo \dosamigos\fileupload\FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'attachment_1[]',
        'url' => ['personal-plan/upload'],
        'gallery' => false,
        'fieldOptions' => [
            'accept' => 'image/*'
        ],
        'clientOptions' => [
            'maxFileSize' => 2000000
        ],
        // ...
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
            'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        ],
    ]);
*/

    echo '</div>';
    ActiveForm::end();



    echo '<div id="files-table" class="col-sm-8">';
  //  echo '<hr>';

    require_once ('files_table.php');


    echo '</div>';

//}
?>
