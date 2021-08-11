<?php
\Yii::$app->language = 'uk-UK';
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormUnit */

$this->title = 'Добавити одиницю нормування';
$this->params['breadcrumbs'][] = ['label' => 'Одиниці нормування', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
