<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */
$fio = 'без виконавця';
//echo '<h4>Kuku:'.Yii::$app->request->get('m').'</h4>';

if($assing = $model->getExecutor()) {
    $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
    if ($resident)
        $fio = $resident->sname . " " . $resident->fname. " " . $resident->lname;
}

$this->title = 'Робота персонального плану для ' . $fio;

$this->params['breadcrumbs'][] = ['label' => 'Роботи сектора', 'url' => ['index', 'm'=>Yii::$app->request->get('m')]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="personal-plan-view max-600">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
      //  if(!\frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id])->parent_job_id)
        echo Html::a('Редагувати', ['update', 'id' => $model->id,'m'=>Yii::$app->request->get('m')], ['class' => 'btn btn-primary'])
        ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->id, 'm'=>Yii::$app->request->get('m')], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
         //   'id',
         //   'resident_id',
            [
                'label' => '№ теми',
                'value' => function ($model){ if($model->theme)return $model->theme->number; return 'Тему видалено';},
            ],

            [
                'attribute' => 'theme_id',
                'value' => function ($model){ if($model->theme)return $model->theme->content; return 'Тему видалено';},
            ],

            [
                'label' => 'Детально про тему',
                'value' => function ($model){ if($model->theme)return $model->theme->desc; return 'Тему видалено';},
            ],
            [                      // the owner name of the model
                'label' => 'Дедлайн теми',
                'value' => function ($model){
                    if($model->theme)return Yii::$app->formatter->asDate($model->theme->deadline, 'dd.MM.yyyy');
                    return '';
                },
            ],
            'content:ntext',


            [
                'label' => 'Виконавець',
                'format' => 'html',
                'value' => function($model) {
                    $fio = null;
                    $add ='';

                    if($assing = $model->getExecutor()) {
                        $resident = \frontend\models\Resident::findOne(['id' => $assing->resident_id]);
                        if ($resident)
                            $fio = $resident->sname . " " . $resident->fname. " " . $resident->lname;
                        //$month = '06';
                        $m = Yii::$app->request->get('m');
                        if($m == 'last'){
                            $month = date("m", strtotime("-1 months"));
                        } else if($m == 'next'){
                            $month = date("m", strtotime("+1 months"));
                        } else {
                            if ($model->started_at && sizeof($mmm = explode('-', $model->started_at)) > 2) {
                                $month = $mmm[1];
                            } else
                            $month = date("m");
                        }


                        // if(Yii::$app->user->can('system')){
                        $add .= '<hr><div class="col-lg-6">';
                        $add .= \frontend\models\Basic::getZagruskaHtml($assing->resident_id, $month);
                        $add .= '</div>';

                        //  }
                    }




                    return $fio . ' ' .
                        Html::a('Профіль', ['resident/view', 'id' => $model->getExecutor()->resident_id],
                            ['class' => 'btn btn-primary', 'style' => 'margin-left: 100px']).$add;
                },
            ],

            [
                'attribute' => 'started_at',
                'value' => Yii::$app->formatter->asDate($model->started_at, 'dd.MM.yyyy'),
                'format' => 'html',
            ],
            [
                'attribute' => 'finished_at',
                'value' => Yii::$app->formatter->asDate($model->finished_at, 'dd.MM.yyyy'),
                'format' => 'html',
            ],
            [
                'attribute' => 'started_at_fact',
                'value' => Yii::$app->formatter->asDate($model->started_at_fact, 'dd.MM.yyyy'),
                'format' => 'html',
            ],
            [
                'attribute' => 'finished_at_fact',
                'value' => Yii::$app->formatter->asDate($model->finished_at_fact, 'dd.MM.yyyy'),
                'format' => 'html',

            ],
           // 'labor',
            'labor',

            [
                'format' => 'html',
                'label' => 'Застосовані норми',
                'value' => function ($model){
                    // $exec = \frontend\models\ExecutorAssignment::findOne(['job_id'  => $model->id, 'parent_job_id' => null]);
                    // if($exec)
                    //if(!$model->resident_id)
                    return \frontend\models\Basic::getNormReport($model);


                    /*
                    return \frontend\models\Basic::getNormReport(
                        \frontend\models\PersonalPlan::findOne(
                            \frontend\models\ExecutorAssignment::findOne(['job_id'  => $model->id])->parent_job_id)
                    );
                    */
                },
            ],



            [
                'label' => 'Трудоміскість по плану н/г',
                'value' => function($model){
                    if($parent_job = frontend\models\ExecutorAssignment::findOne(['job_id'=>$model->id])->parentJob)
                        return $parent_job->labor;}
            ],


            [
                'format' => 'html',
                'label' => 'Застосовані норми по плану',
                'value' => function ($model){
                    $exec = \frontend\models\ExecutorAssignment::findOne(['job_id'  => $model->id, 'parent_job_id' => null]);
                    if($exec)
                        //if(!$model->resident_id)
                        return null;//\frontend\models\Basic::getNormReport($model);
                    return \frontend\models\Basic::getNormReport(
                        \frontend\models\PersonalPlan::findOne(
                            \frontend\models\ExecutorAssignment::findOne(['job_id'  => $model->id])->parent_job_id)
                    );
                },
            ],
          //  'created_at',
            [
                'attribute' => 'status',
                'value' => $model->statuses[$model->status],
            ],


            'desc',

        ],
    ]);

    $searchModel = new \frontend\models\CdFilesSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andFilterWhere(['status'=>1]);

    if(Yii::$app->user->can('viewFact')) {
        $dataProvider->query->orFilterWhere(['job_id' => $model->id]);
    }
    require_once ('files_table.php');

    ?>

</div>
