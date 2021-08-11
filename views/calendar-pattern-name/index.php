<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CalendarPatternNameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблони робочих графіків';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-pattern-name-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Створити шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'name',
            'info',
            'year',
            'modified',
            [
                    'label' => 'Робочих днів',
                    'content' => function ($model){
                    return $model->getWorkingDays();//(date('L')?366:365) - sizeof($model->calendarPatterns);
                    }
            ],

            [
                    'label' => 'Автор(може редагувати)',
                    'content' => function ($model){
                    if($model->resident) {
                        if($model->resident->div)
                        return $model->resident->sname . ' ' . $model->resident->fname . ' (' . $model->resident->div->name . ')';
                    }
                    return null;
                    }
            ],

            [
                   // 'caption' => 'sdsd',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}  {delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
