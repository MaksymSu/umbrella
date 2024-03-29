<?php

/*
if ($_SERVER['REMOTE_ADDR'] != '192.168.130.50') {
//if(Yii::$app->user->identity->name != 'lb'){
    echo '<h1>-- У розробці--</h1>';
    exit();
}
*/


\Yii::$app->language = 'uk-UK';

/* @var $this \yii\web\View */
/* @var $content string */
date_default_timezone_set('Europe/Kiev');
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>



<div class="wrap">
    <?php
    if (Yii::$app->user->isGuest) {
        $brand = Yii::$app->name;
    }else {
        $brand = '<img src="images/logo8.png" class="small-logo"/>';
    }
        NavBar::begin([
            //'brandLabel' => Yii::$app->name,
            'brandLabel' => $brand,
            //'brandUrl' => Yii::$app->homeUrl,
            'brandUrl' => ['/site/login'],

            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top tt',
            ],
        ]);



    $menuItems = [
       // ['label' => 'Додому', 'url' => ['/site/index']],
        //['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Контакти', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Реєстрація', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Вхід', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Вийти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
