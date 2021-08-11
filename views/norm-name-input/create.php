<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\NormNameInput */

$this->title = 'Створити норму';
$this->params['breadcrumbs'][] = ['label' => 'Норми', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="norm-name-input-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
