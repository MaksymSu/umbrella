
<?php


\Yii::$app->language = 'uk-UK';


use kartik\tree\TreeView;

//echo '<h2>'.$id.'</h2>';

echo TreeView::widget([
    'query' => \frontend\models\Tree::find()->
    where(['theme_id' => $id])->
    addOrderBy('root, lft'),

    'headingOptions' => ['label' => 'Store'],
    'rootOptions' => ['label'=>'<span class="text-primary">Дерево виробу</span>'],
    'topRootAsHeading' => true, // this will override the headingOptions
    'fontAwesome' => true,
    'isAdmin' => false,
    'showIDAttribute' => false,

    'softDelete' => true,
    'cacheSettings' => ['enableCache' => true]
]);

