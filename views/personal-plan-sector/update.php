<?php

use yii\helpers\Html;
$fio = 'без виконавця';
if($assing = $model->getExecutor()) {
    $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
    if ($resident)
        $fio = $resident->sname . " " . $resident->fname. " " . $resident->lname;
}

$this->title = 'Редагування персонального плану ' . $fio;
$this->params['breadcrumbs'][] = ['label' => 'Роботи сектора', 'url' => ['index', 'id' => $model->id,
    'm'=>Yii::$app->request->get('m')]];
//$this->params['breadcrumbs'][] = ['label' => substr($model->content, 0, 20).'..', 'url' => ['index', 'id' => $model->id,
 //   'm'=>Yii::$app->request->get('m')]];
$this->params['breadcrumbs'][] = 'Редагування';
?>
<div class="personal-plan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'create' => false,
    ]) ?>

</div>
