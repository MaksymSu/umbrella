<!-- on your view layout file HEAD section -->
<!-- on your view layout file HEAD section -->
<script src="tree.js" crossorigin="anonymous"></script>
<?php


\Yii::$app->language = 'uk-UK';


use kartik\tree\TreeView;
use frontend\models\Tree;

echo TreeView::widget([
    'query' => \frontend\models\Tree::find()->addOrderBy('root, lft'),
    'headingOptions' => ['label' => 'Store'],
    'rootOptions' => ['label'=>'<span class="text-primary">Вироб</span>'],
    'topRootAsHeading' => true, // this will override the headingOptions
    'fontAwesome' => true,
    'isAdmin' => false,
    'showIDAttribute' => false,

    'softDelete' => true,
    'cacheSettings' => ['enableCache' => true]
]);

?>



