<?php
\Yii::$app->language = 'uk-UK';

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonalPlan */

$this->title = $model->content;
$this->params['breadcrumbs'][] = ['label' => 'Зміст мого персонального плану', 'url' => ['index', 'm'=>Yii::$app->request->get('m')]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="personal-plan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if($model->status != 2) {
            echo Html::a('Редагувати', ['update', 'id' => $model->id, 'm'=>Yii::$app->request->get('m')], ['class' => 'btn btn-primary']);
        }
        ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
           // 'resident_id',
            [                      // the owner name of the model
                'label' => 'Роботу видав',
                'format' => 'html',
                'value' => function($model){$resident = $model->getSorcerer();


                    $add ='';
                if($assing = $model->getExecutor()) {
                    //$month = '06';
                    $m = Yii::$app->request->get('m');
                    if ($m == 'last') {
                        $month = date("m", strtotime("-1 months"));
                    } else if ($m == 'next') {
                        $month = date("m", strtotime("+1 months"));
                    } else {
                        if ($model->started_at && sizeof($mmm = explode('-', $model->started_at)) > 2) {
                            $month = $mmm[1];
                        } else
                        $month = date("m");
                    }


                      //  if(Yii::$app->user->can('system')){
                    $add .= '<hr><div class="col-lg-6">';
                    $add .= \frontend\models\Basic::getZagruskaHtml($assing->resident_id, $month);
                    $add .= '</div>';

                    //    }

                }
                    return $resident->sname.' '.$resident->fname.' '.$resident->lname.
                                Html::a('Профіль', ['resident/view', 'id' => $resident->id],
                                    ['class' => 'btn btn-primary', 'style'=>'margin-left: 100px']).$add;
                            },
            ],
            [                      // the owner name of the model
                'label' => 'Номер теми',
                'value' => function ($model){
                    if($model->theme)return $model->theme->number;
                    return '';
                },
            ],
            [                      // the owner name of the model
                'label' => 'Тема',
                'value' => function ($model){
                            if($model->theme)return $model->theme->content;
                            return '';
                            },
            ],

            [                      // the owner name of the model
                'label' => 'Дедлайн теми',
                'value' => function ($model){
                    if($model->theme)return Yii::$app->formatter->asDate($model->theme->deadline, 'dd.MM.yyyy');
                    return '';
                },
            ],


            'content:ntext',
          //  'started_at_fact',
          //  'finished_at_fact',
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
           // 'created_at',
            [
                    'label' => 'Стан',
                    'value' => $model->statuses[$model->status],
            ],
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

            /*

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
            */
            'desc',
        ],
    ]) ?>


    <?php
    $searchModel = new \frontend\models\CdFilesSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andFilterWhere(['status'=>1]);//, 'resident_id'=>\frontend\models\Resident::findOne(['user_id'=>Yii::$app->user->id])->id]);
    require_once ('files_table_view.php');
    ?>
</div>
