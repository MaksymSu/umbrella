<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */

$this->title = 'Редагування роботи мого індивідуального плану';
$this->params['breadcrumbs'][] = ['label' => 'Зміст мого персонального плану', 'url' => ['index','m'=>Yii::$app->request->get('m')]];
$this->params['breadcrumbs'][] = 'Редагувати';


?>
<div class="personal-plan-update">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= $this->render('_form', [
        'model' => $model,
        //'route' => $route,
        'm'=>$m,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
