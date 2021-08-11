<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Theme */

$this->title = mb_substr($model->content,0,30).'..';
$this->params['breadcrumbs'][] = ['label' => 'Теми', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="theme-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          //  'id',
            'number',
            'step',
            'content:ntext',
        //    'type',
        //    'born',
        //    'status',
            'desc:ntext',
           // 'deadline',
             [
                  'attribute' => 'deadline',
                 'value' => Yii::$app->formatter->asDate($model->deadline, 'dd.MM.yyyy'),
                 'format' => 'html',
             ],

            [
                'attribute' => 'master_div_id',
                'value' => function($model){if($model->div)return $model->div->name;},

            ],
            [
                'attribute' => 'no_norms',
                'value' => function($model){if($model->no_norms)return 'Так, не застосовувати';return 'Ні, застосовувати';},
            ],

            [
            'attribute' => 'status',
            'value' => function($model){if($model->status)return 'Так';return 'Ні';},
            ],

            [
                'attribute' => 'norm_percent_flag',
                'value' => function($model){if($model->norm_percent_flag)return 'Так';return 'Ні';},
            ],

        ],
    ]) ?>

</div>
