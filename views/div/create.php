<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Div */

$this->title = 'Добавити відділ';
$this->params['breadcrumbs'][] = ['label' => 'Відділи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="div-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
