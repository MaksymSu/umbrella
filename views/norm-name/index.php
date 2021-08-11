<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NormNameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Калькуляція норм для роботи "'. mb_substr(\frontend\models\PersonalPlan::findOne($job_id)->content,  0, 100).'..."';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-inline">
<?= Html::a('Застосувати розрахунок', ['norm-name/set', 'job_id' => $job_id, 'm'=>Yii::$app->request->get('m')], ['class' => 'btn btn-success', 'style'=>'margin-right: 10px']); ?>
<?= Html::a('Не застосовувати', ['norm-name/set2', 'job_id' => $job_id, 'm'=>Yii::$app->request->get('m')], ['class' => 'btn btn-default']); ?>

<?= Html::a('Інструкція', ['norm-name/instruction'], ['class' => 'btn btn-warning', 'style' => 'float:right', 'target'=>'_blank']); ?>


</div>
<div class="norm-name-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



<div class="col-lg-5">

    <?php
    $dataProvider->pagination->pageSize = 6;
    //$dataProvider->pagination->limit = 20;
    echo '<h3>Пошук норми у базі</h3>';

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'Перша',
            'lastPageLabel'  => 'Ост'
        ],
        'columns' => [
          //  ['class' => 'yii\grid\SerialColumn'],

         //   'id',
       //    'code',
            //'content:ntext',
            [
                'attribute'=>'code',
                'format'=>'text',
                'contentOptions'=>['style'=>'width: 100px;white-space: normal;'] ,
            ],
            [
                'attribute'=>'content',
                'format'=>'ntext',
                'contentOptions'=>['style'=>'width: 350px;white-space: normal;'] ,
            ],
          //  'status',
          //  'updated_at',


            [
                'label' => 'Одиниця',
                'content' => function($model){
                    //if($norms_arr = $model->norms) {
                    //    if($norms_arr[0]->unit) {
                    //        return $norms_arr[0]->unit->content;
                    //    }
                    //}
                    //return null;
                    return $model->unit->content;
                }
            ],

           // 'unit',

            [
                'format' => 'html',
                'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                'content' => function ($model) use ($job_id) {
                    return Html::button('', [ 'class' => 'btn btn-primary glyphicon glyphicon-share-alt', 'onclick' =>
                        '$.get( "'.\yii\helpers\Url::toRoute('/norm-name/add').'", { id: '.$model->id.', job_id: '.$job_id.' } )
                            .done(function( data ) {
                        $( "#wwww" ).html( data );
                    }
                    );'
                    ]);
                },
            ],
        ],
    ]); ?>
</div>


    <div id="wwww" class="col-lg-7">
    <?php


    echo '<h3>Вибрані норми: <span  id="hhh">'.\frontend\models\Basic::getTotalAll($job_id).'</span> н/г</h3>';

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
            return Html::button('', [ 'class' => 'btn btn-default glyphicon glyphicon-remove', 'onclick' =>
            '$.get( "'.\yii\helpers\Url::toRoute('/norm-name/delete2').'", { id: '.$model->id.', job_id: '.$model->job_id.' } )
            .done(function( data ) {
            $( "#wwww" ).html( data );
            }
            );'
        ]);
        }

        ]

        ],

            [
                'label'=> 'Номер',
                'contentOptions'=>['style'=>'width: 100px;white-space: normal;'],
                'content' => function($model){
                    return $model->norm->name->code;
                }
            ],

        [
        'label'=> 'Норма',
        'contentOptions'=>['style'=>'width: 350px;white-space: normal;'],
        'content' => function($model){
        return $model->norm->name->content;
        }
        ],

        [
        'label'=> 'Новизна',
        'format'=> 'html',
        'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
        'content' => function($model){

            $model->novelty = array_search($model->norm->novelty, \frontend\models\Norm::$novelties);
            $model->difficulty = array_search($model->norm->difficulty, \frontend\models\Norm::$difficulties);
          return  Html::activeDropDownList($model, 'novelty',
                $model->novelties, ['class'=>'form-control',  'id' => 'nov-'.$model->id,

                  'onchange' =>
                      '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/novelty').'", { 
                        nov: $(this).val(),
                        dif: '.$model->difficulty.',
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.'
                         
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        );'

                  ]);
        }
        ],

        [
        'label'=> 'Складність',
        'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
        'content' => function($model){
            $model->novelty = array_search($model->norm->novelty, \frontend\models\Norm::$novelties);
            $model->difficulty = array_search($model->norm->difficulty, \frontend\models\Norm::$difficulties);
            return  Html::activeDropDownList($model, 'difficulty',
                $model->difficulties, ['class'=>'form-control',  'id' => 'dif-'.$model->id,

                    'onchange' =>
                        '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/novelty').'", { 
                        nov: '.$model->novelty.',
                        dif: $(this).val(),
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.'

                         
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        );'

                ]);
        }
        ],
       // 'norm_id',
       // 'job_id',

        //'format_id',
            [
                    'format' => 'html',
                'attribute' => 'format_id',
                'contentOptions'=>['style'=>'width: 90px;white-space: normal;'],
                'content' => function($model){
                    if($model->norm->name->unit) {
                        $unit = $model->norm->name->unit->content;
                        if ($model->isInUnits($unit)) {
                            return 'Аркуш' . Html::activeDropDownList($model, 'format_id',
                                    $model->units, ['class' => 'form-control', 'id' => 'format-'.$model->id,

                                        'onchange' =>
                                            '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/format').'", { 
                        format_id: $(this).val(),
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.'

                                                 
                         } )
                            .done(function( data ) {
                                $( "#wwww" ).html( data );
                            }
                        );'

                                        ]);
                        }
                        return $unit;
                    }
                    return null;
                }
            ],
            [
                'attribute' => 'value',
                'content' => function($model){
                    //return '<input type="number" id="value-'.$model->id.'"
                    //        min="1" max="1000" class="form-control" value="'.$model->value.'">';
                    return Html::activeInput('number', $model, 'value', ['class' => 'form-control', 'min'=>1,

                        'onchange' =>
                            '
                        $.get( "'.\yii\helpers\Url::toRoute('/norm-name/value').'", { 
                        value: $(this).val(),
                        norm_job_id: '.$model->id.',
                        job_id: '.$model->job_id.',
                       
                                             
                         } )
                            .done(function( data ) {
                                $( "#total-'.$model->id.'" ).html( data );
                            }
                        );'

                    ]);
                }
            ],

            [
                'label'=> 'н/г',
                'contentOptions'=>['style'=>'width: 40px;white-space: normal;'],
                'content' => function($model){
                    //return $model->norm->value;
                    return '<div id="total-'.$model->id.'">'.\frontend\models\Basic::getTotal($model).'</div>';
                }
            ],
        //   ['class' => 'yii\grid\ActionColumn'],
        ],
        ]);


        ?>
    </div>
    <?php Pjax::end(); ?>

</div>
