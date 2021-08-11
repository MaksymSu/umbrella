<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonalPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Індивідуальні плани по сектору';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-plan-index" >

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавити роботу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],


            //'id',
            // 'resident_id',

            [
                'attribute'=>'theme_num',
                'content'=>function($model){
                    if(isset($model->theme))
                        return $model->theme->number;
                    else return '';
                }
            ],
            [
                'attribute'=>'theme_content',
                'format'=>'html',
                'content'=>function($model){
                    if(isset($model->theme))
                        return str_replace("\n", '<br>', $model->theme->content);
                    else return '';
                }
            ],

            'content:ntext',

            [
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],,
                'attribute' => 'executor',
                'content' => function($model){
                    if($assing=$model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                        if ($resident)
                            return $resident->sname . "<br>" . $resident->fname. " " . $resident->lname;
                        return '>Невідомий<';
                    }
                    return null;
                }
            ],
            /*
        [
            'attribute' => 'started_at',
            'content' => function($model){
            return Yii::$app->formatter->asDate($model->started_at, 'd.M.Y');
            }
        ],
*/
            'started_at',
            'finished_at',
            'started_at_fact',
            'finished_at_fact',
            'labor',

            //'created_at',
            'status',
            //'desc',

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

