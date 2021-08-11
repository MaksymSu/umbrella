<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ResidentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Резиденти';
$this->params['breadcrumbs'][] = $this->title;

$m = $searchModel->m;
?>
<div class="resident-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php

    if(Yii::$app->user->can('editResidents')){
    echo '<p>';
        echo Html::a('Добавити резидента', ['create'], ['class' => 'btn btn-success']);
    echo '</p>';
    }
?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
          //  ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
           //     'urlCreator' => function ($url, $model) use ($m) {
          //          return 'index.php?r=personal-plan/'.$url.'&id='.$model->id.'&m='.$m;
           //     },
                // 'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{view} {update}{delete}',
                'buttons' => [

                    'update' => function ($url, $model) {
                        if(!Yii::$app->user->can('editResidents'))return null;
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'lead-update'),
                        ]);
                    },

                    'delete' => function ($url, $model) {
                        if(!Yii::$app->user->can('editResidents'))return null;
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                            'data' => [
                                'confirm' => 'Ви впевнені?',
                                'method' => 'post',
                            ],
                        ]);
                    },


                ],
            ],

            // 'id',
            [
                'attribute' => 'sname',
                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,
            ],
            [
                'attribute' => 'fname',
                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,
            ],
            [
                'attribute' => 'lname',
                'contentOptions'=>['style'=>'width: 200px;white-space: normal;'] ,
            ],

           // 'fname',
           // 'lname',
          //  'tab',
          //  'dob',
            //'age',
           // 'photo',
/*
            [
                'attribute'=>'struct_name',
                'content'=>function($model){
                    if(isset($model->struct))
                        return $model->struct->name;
                    else return '';
                }
            ],
*/

            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'struct_name',
                'contentOptions'=>['style'=>'width: 160px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'struct_id',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Structs::find()->all()
                        , 'id', 'name'),
                    ['class'=>'form-control','prompt' => '-Виберіть-']),

                'content' => function($model){
                    return $model->struct->name;
                },


            ],


/*
            // 'div_id',
            [
                'attribute'=>'div_name',
                'content'=>function($model){
                    if(isset($model->div))
                        return $model->div->name;
                    else return '';
                }
            ],
*/
            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'div_name',
                'contentOptions'=>['style'=>'width: 300px;white-space: normal;'] ,

                'filter'=>Html::activeDropDownList($searchModel, 'div_id',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Div::findAll(['struct_id' => $searchModel->struct_id])
                        , 'id', 'name'),
                    ['class'=>'form-control','prompt' => '-Виберіть-']),

                'content' => function($model){
                    return $model->div->name;
                }
            ],


/*
            [
                'attribute'=>'sector_name',
                'content'=>function($model){
                    if(isset($model->sector))
                        return $model->sector->name;
                    else return '';
                }
            ],
*/
            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'sector_name',


                'filter'=>Html::activeDropDownList($searchModel, 'sector_id',
                    \yii\helpers\ArrayHelper::map(
                        \frontend\models\Sectors::findAll(['struct_id' => $searchModel->struct_id, 'div_id' => $searchModel->div_id])
                        , 'id', 'name'),
                    ['class'=>'form-control','prompt' => '-Виберіть-']),

                'content' => function($model){
                    return $model->sector->name;
                }
            ],

            [
                'attribute' => 'type',
                'filter'=>Html::activeDropDownList($searchModel, 'type', $searchModel->types, ['class'=>'form-control','prompt' => '-Всі-']),
                'format' => 'raw',
                'content' => function($model){
                    if(!$model->type)
                    return $model->types[$model->type];
                    return '<b>'.$model->types[$model->type].'</b>';
                },
            ],
            //'desc',
            //'div_id',
           // 'sector_id',

        ],
    ]); ?>



    <?php Pjax::end(); ?>
</div>
<?php
if(Yii::$app->user->can('editResidents')){
    $residents = \frontend\models\Resident::find()->where(['user_id' => NULL]);



    echo '<details><summary>Незареєстрованих: ' .$residents->count().'</summary>';
    foreach ($residents->all() as $res){
        echo $res->sname.' '. $res->fname.' '. $res->lname . '<br>';
    }
    echo '</details>';
}

?>
