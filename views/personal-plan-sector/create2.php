<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */

$this->title = 'Добавити роботу з плана-графіка (у розробці)';
$this->params['breadcrumbs'][] = ['label' => 'Роботи', 'url' => ['index', 'id' => $model->id,
    'm'=>Yii::$app->request->get('m')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-plan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form2', [
        'model' => $model,
        'model_exec' =>$model_exec,
        'create' => true,
    ]) ?>

</div>
