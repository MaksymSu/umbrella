<?php

namespace frontend\controllers;

use frontend\models\ExecutorAssignment;
use frontend\models\Planning\ExecDivAssignment;
use frontend\models\Sectors;
use frontend\models\Theme;
use Yii;
use frontend\models\Planning\PersonalPlan;
use frontend\models\Planning\PersonalPlanSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlanningController implements the CRUD actions for PersonalPlan model.
 */
class PlanningController extends Controller
{
    public $layout = 'sidenav';


    public function behaviors()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PersonalPlan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonalPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('graphic/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PersonalPlan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $url = Url::previous();
        if(strpos($url, 'PersonalPlanSearch')) return $this->redirect($url);



        return $this->redirect(['index']);
        return $this->render('graphic/view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionFilter(){
        $url = Url::previous();
        if(strpos($url, 'PersonalPlanSearch')) return $this->redirect($url);
        return $this->redirect(['index']);
    }

    /**
     * Creates a new PersonalPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PersonalPlan();
        $model -> setResident();
        $model->save();

        $exec_div = new ExecDivAssignment();
        $exec_div->job_id = $model->id;
        $exec_div->div_id = $model->getMyDiv()->id;
        $exec_div->master_div_id = $exec_div->div_id;
        $exec_div->save();


        $url = Url::previous();
        if(strpos($url, 'PersonalPlanSearch') && Yii::$app->user->can('system')) {
            //$model->theme_id = 23;//
            //$model->save();

            //Url::previous('theme_id_search');
            return $this->redirect($url);
        }
        return $this->redirect(['index']);
        //$this->actionFilter();
        /*
        if ($model->load(Yii::$app->request->post())) {
            $model->setResident();
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('graphic/create', [
            'model' => $model,
        ]);
        */
    }

    /**
     * Updates an existing PersonalPlan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model_glass = new PersonalPlan();

        if ($model->load(Yii::$app->request->post())) {

            //$model->
            $model->save();

            $div_ass = ExecDivAssignment::findOne(['job_id' => $model->id]);
            $div_ass->div_id = $model->executor_div_id;
            $div_ass->sector_id = $model->executor_sector_id;
            //$div_ass->master_div_id = $div_ass->div_id;
            $div_ass->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->executor_div_id = $this->findExecDiv($id);
        $model->executor_sector_id = $this->findExecSector($id);
        return $this->render('graphic/update', [
            'model' => $model,
            //'back_url'=>Yii::$app->request->getUrl(),
        ]);
    }

    /**
     * Deletes an existing PersonalPlan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $master_div = null;
        if($model->theme)$master_div=$model->theme->masterDiv->id;
        if($master_div && $model->getMyDiv()->id != $master_div){
            return $this->redirect(['index']);
        }
        $model->delete();
        ExecDivAssignment::deleteAll(['job_id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the PersonalPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonalPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PersonalPlan::findOne($id)) !== null) {
          //  $exec_div_record = ExecDivAssignment::findOne(['job_id' => $model->id]);
          //  if($exec_div_record)$model->executor_div_id = $exec_div_record->div_id;
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findExecDiv($id)
    {
        if (($model = ExecDivAssignment::findOne(['job_id' => $id])) !== null) {
            return $model->div_id;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findExecSector($id)
    {
        if (($model = ExecDivAssignment::findOne(['job_id' => $id])) !== null) {
            return $model->sector_id;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSetSector($id){
        if($id == PersonalPlan::getMyDiv()->id) {
            echo '<script>document.getElementById("lb-sector").style.display = "block";</script>';
            $rows = Sectors::find()->where(['div_id' => $id])->all();

             // echo "<option>Не визначено</option>";

            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    echo "<option value='$row->id'>" . $row->name . "</option>";
                }
            }
        }else echo '<option></option><script>document.getElementById("lb-sector").style.display = "none";</script>';
    }

    public function actionSaveForm($id,
                                   $theme_id,
                                    $div_id,
                                    $sector_id,
                                    $content,
                                    $started_at,
                                    $finished_at, $dis){


        $model = PersonalPlan::findOne($id);

        if(!$dis) {
            $model->theme_id = $theme_id;
            $model->content = $content;
        }
        $model->started_at = $started_at;
        $model->finished_at = $finished_at;
        $model->save();

        $div_ass = ExecDivAssignment::findOne(['job_id' => $model->id]);
        $div_ass->div_id = $div_id;
        $div_ass->sector_id = $sector_id;
        $div_ass->save();
    }

    protected function guard(){
        if(!Yii::$app->user->can('planning'))
            return $this->redirect(['index']);
    }



    public function actionSetTheme($id){
            if(Theme::findOne($id)->no_norms)
             echo '<script>document.getElementById("personalplan-labor").removeAttribute("disabled");</script>';
            else {
                echo '<script>document.getElementById("personalplan-labor").disabled = true;</script>';
            }
    }
}
