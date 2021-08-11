<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */

$this->title = 'Добавити роботу виконавцю в персональний план';
$this->params['breadcrumbs'][] = ['label' => 'Роботи', 'url' => ['index', 'id' => $model->id,
    'm'=>Yii::$app->request->get('m')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'create' => true,
    ]) ?>

</div>
