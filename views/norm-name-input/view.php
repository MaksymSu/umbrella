<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormNameInput */

$this->title = $model->content;
$this->params['breadcrumbs'][] = ['label' => 'Норми', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="norm-name-input-view col-lg-8">

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
         //   'id',
            'content:ntext',
            'code',
            //'variants',
            [
                'format' => 'html',
                'attribute' => 'variants',
                'value' => $model->drawTable(),
            ],
            [
                'attribute' => 'status',
                'value' => $model->statuses[$model->status],
            ],
            'updated_at',
        ],
    ]) ?>

</div>
