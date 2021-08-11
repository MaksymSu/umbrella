<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Structs */

$this->title = 'Добавити структуру';
$this->params['breadcrumbs'][] = ['label' => 'Structs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="structs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
