<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


//\frontend\models\Norm::loadNorms();




/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Norms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);


    ?>

    <?php
    /*
    ?>
    <p>
         Html::a('Create Norm', ['create'], ['class' => 'btn btn-success'])
    </p>
*/
    ?>

    <?php $dataProvider->pagination->pageSize = 6;
    echo '<h3>Пошук норми</h3>';

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
         //   ['class' => 'yii\grid\SerialColumn'],
            [
              //  'class' => 'yii\grid\ActionColumn',
               // 'context' => $this->context,
                'format' => 'html',
                'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                'content' => function ($model) {

                       // return Html::a('<span class="glyphicon glyphicon-triangle-bottom"></span>', ['norm/add' , 'id'=>$model->id],
                       //     ['onclick'=>'<script>alert("lala");</script>']
                       //     );

                    return Html::button('', [ 'class' => 'btn btn-primary glyphicon glyphicon-plus', 'onclick' =>
                        '$.get( "'.\yii\helpers\Url::toRoute('/norm/add').'", { id: '.$model->id.' } )
                            .done(function( data ) {
                        $( "#wwww" ).html( data );
                    }
                    );'
                      //  '(function ( $event ) { alert("Button 3 clicked"); })();'
                    ]);
                    },
               // 'template' => '{add}'
            ],
          //  'id',
         //   'name_id',
            [
                'attribute' => 'content_str',
                'format' => 'text',
                'content' => function($model){
                return $model->name->content;
                }
            ],

         //   'unit_id',
            [
                'attribute' => 'unit_id',
                'format' => 'text',
                'content' => function($model){
                   // if(strpos($model->unit->content, ['A']))
                    return $model->unit->content;
                }
            ],


            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'novelty',
                'contentOptions'=>['style'=>'width: 160px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'novelty_str',
                    $searchModel->novelties,
                    ['class'=>'form-control','prompt' => '-Виберіть-']),// 'options'=>['0'=>["Selected"=>true]]]),


            ],
         //   'novelty',
           // 'difficulty',
            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'difficulty',
                'contentOptions'=>['style'=>'width: 160px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'difficulty_str',
                    $searchModel->difficulties,
                    ['class'=>'form-control','prompt' => '-Виберіть-']),


            ],
            //'status',
            //'updated_at',
            'value',




          //  ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


<div id="wwww">
    <?php
    echo '<h3>Вибрані норми</h3>';

    echo GridView::widget([
    'dataProvider' => $dataProviderNJ,
    //   'filterModel' => $searchModelNJ,
    'columns' => [
    //'id',
    [

    'class' => 'yii\grid\ActionColumn',

    'template' => '{delete}',
    'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],

    'buttons' => [


    'delete' => function ($url, $model, $key) {
    return Html::button('', [ 'class' => 'btn btn-warning glyphicon glyphicon-minus', 'onclick' =>
    '$.get( "'.\yii\helpers\Url::toRoute('/norm/delete2').'", { id: '.$model->id.' } )
    .done(function( data ) {
    $( "#wwww" ).html( data );
    }
    );'
    //  '(function ( $event ) { alert("Button 3 clicked"); })();'
    ]);
    // return  Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete2', 'id'=>$key]);//, ['linkOptions' => ['data-method' => 'post']]);
    }

    ]

    ],



    [
    'label'=> 'Вибрані норми',
    'content' => function($model){
    return $model->norm->name->content;
    }
    ],
    [
    'label'=> 'Новизна',
    'content' => function($model){
    return $model->norm->novelty;
    }
    ],
    [
    'label'=> 'Складність',
    'content' => function($model){
    return $model->norm->difficulty;
    }
    ],
    'norm_id',
    'job_id',

    'format_id',


    //   ['class' => 'yii\grid\ActionColumn'],
    ],
    ]);
    ?>
</div>


    <?php Pjax::end(); ?>

 </div>
