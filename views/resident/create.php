<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Resident */

$this->title = 'Добавити резидента';
$this->params['breadcrumbs'][] = ['label' => 'Резиденти', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
