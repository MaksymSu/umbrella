<!-- on your view layout file HEAD section -->
<!-- on your view layout file HEAD section -->
<script src="tree.js" crossorigin="anonymous"></script>
<script defer src="all.js"></script>
<script defer src="v4-shims.js"></script>



<?php


\Yii::$app->language = 'uk-UK';


use kartik\tree\TreeView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Дерево виробу';
$this->params['breadcrumbs'][] = $this->title;



$all_themes_arr = ArrayHelper::map(\frontend\models\Theme::find()->where(['!=','number',700])
    ->all(),
    'id',function ($data){return $data->number.' - '.$data->content;});


echo Html::activeDropDownList($model, 'theme_id', $all_themes_arr,
    ['class' => 'form-control',
        'prompt'=>'--Виберіть тему--',

        'onchange'=>'
        $("#tree-theme_id").val($(this).val());
        window.location.href = "'.\yii\helpers\Url::toRoute('/main-tree/index').'&theme_id=" + $(this).val()

                     ',
        ]);






?>





    <div id="main-tree">


<?php
echo '<br>';
//echo $model->theme_id ?>


<?php
if($model->theme_id)
    echo TreeView::widget([
    'query' => \frontend\models\Tree::find()->
    where(['theme_id' => $model->theme_id])->
    addOrderBy('root, lft'),

        'headingOptions' => ['label' => 'Дерево конструкції'],
    'rootOptions' => ['label'=>'<span class="text-primary">'.\frontend\models\Themes::findOne($model->theme_id)->number.'</span>'],
    'topRootAsHeading' => true, // this will override the headingOptions
    'fontAwesome' => true,
    'isAdmin' => false,
    'showIDAttribute' => false,

    'softDelete' => true,
    'cacheSettings' => ['enableCache' => false],
        'nodeAddlViews' => [
            kartik\tree\Module::VIEW_PART_2 => '@frontend/views/main-tree/_form',

        ],
        'defaultChildNodeIcon' => '<i class="fa fa-cog"></i>',
        'defaultParentNodeOpenIcon' => '<i class="fa fa-cog"></i>',
        'defaultParentNodeIcon' => '<i class="fa fa-cogs"></i>'
]);
   ?>


    </div>


