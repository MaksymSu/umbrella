<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormUnit */

$this->title = 'Редагувати одиницю нормування: ' . $model->content;
$this->params['breadcrumbs'][] = ['label' => 'Одиниці нормування', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->content, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагувати';
?>
<div class="norm-unit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
