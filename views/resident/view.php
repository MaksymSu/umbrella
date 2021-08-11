<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Resident */

$this->title = $model->sname.' '.$model->fname;
$this->params['breadcrumbs'][] = ['label' => 'Резиденти', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="resident-view">

    <h1><?= Html::encode($this->title) ?></h1>
<?php
    if(Yii::$app->user->can('editResidents')) {

        echo '<p>';
        echo Html::a('Оновити', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        echo ' ';
        echo Html::a('Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]);

        echo '</p>';
    }
?>


    <?php

    if(Yii::$app->user->can('viewResidentsSecrets'))
        $attributes = [
            'user_name',
         //   'posada_name',
            'posada_desc',
            'sname',
            'fname',
            'lname',
            'struct_name',
            'div_name',
            'sector_name',
            'tab',
            [
                'attribute' => 'work_mode',
                'value' => $model->work_modes[$model->work_mode],
            ],

            [
                'attribute' => 'type',
                'value' => $model->types[$model->type],
            ],

            [
                'attribute' => 'dob',
                'value' =>Yii::$app->formatter->asDate($model->dob, 'dd.MM.yyyy'),
                'format' => 'html',
            ],

            [
                'label' => 'Фото',
                'attribute' => 'file',
                'format' => 'html',
            ],

            [
                'label' => 'Телефони',
                'value' => implode(', ', \yii\helpers\ArrayHelper::map(\frontend\models\Phone::findAll(['resident_id' => $model->id]), 'id', 'number'))
            ],

            'desc',
            ];

    else $attributes = [
        'posada_desc',
        'sname',
        'fname',
        'lname',
        'struct_name',
        'div_name',
        'sector_name',
        [
            'label' => 'Телефони',
            'value' => implode(', ', \yii\helpers\ArrayHelper::map(\frontend\models\Phone::findAll(['resident_id' => $model->id]), 'id', 'number'))
        ],
    ];

    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,

    ]) ?>

</div>
