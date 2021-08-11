<?php

namespace frontend\controllers;

use DeepCopy\f001\B;
use frontend\models\Basic;
use frontend\models\CdFilesSearch;
use frontend\models\ExecutorAssignment;
use frontend\models\JobNode;
use frontend\models\Planning\ExecDivAssignment;
use frontend\models\Resident;
use Yii;
use frontend\models\PersonalPlan;
use frontend\models\PersonalPlanSectorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PersonalPlanSectorController implements the CRUD actions for PersonalPlan model.
 */
class PersonalPlanSectorController extends Controller
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
        if(!Yii::$app->user->can('taskForSector')){
            $this->redirect(['site/error']);
        }
        $searchModel = new PersonalPlanSectorSearch();
        $searchModel->m = Yii::$app->request->get('m');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
          //  'm' => Yii::$app->get('m'),
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
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new PersonalPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new PersonalPlan();
        $model->idid = 0;

        if ($model->load(Yii::$app->request->post())) {

           // $model->save();
            if($model->save()) {
                $this->upCreate($model);
                return $this->redirect(['view', 'id' => $model->id, 'm' => Yii::$app->request->post('m')]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreate2()
    {

        $model_exec = new ExecutorAssignment();
           // $model = new \frontend\models\Planning\PersonalPlan();
        $model = new PersonalPlan();
        $model->started_at = date('Y-m-d');



        if ($model->load(Yii::$app->request->post())) {
            $job = PersonalPlan::findOne($model->idid);

            $model->labor = round($job->labor/100 * $model->percent, 2);
            if($model->labor == $job->labor) {
                $model->content = $job->content;
            }else{
                $model->content = $job->content.' ('.$model->percent.'%)';
            }


            if($model->save()) {
                $this->upCreate2($model, $model_exec);
                return $this->redirect(['view', 'id' => $model->id, 'm' => Yii::$app->request->post('m')]);
            }

          //  $this->upCreate2($model, $model_exec);
           //     return $this->redirect(['view', 'id' => $model->idid, 'm' => Yii::$app->request->post('m')]);
           // }
        }

        return $this->render('create2', [
            'model' => $model,
            'model_exec' => $model_exec,
        ]);
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
        $model->idid = 0;

        //защита от попытки взлома через подмену id персонального плана
        $this->guard($model->id);
       // $this->guard2($model->id);


        if ($model->load(Yii::$app->request->post())) {

            //$model->started_at = Yii::$app->formatter->asDate($model->started_at, 'yyyy-MM-dd');
            if($model->save()) {

               // if(Yii::$app->user->can('system')) { //lb
               //     $this->saveNodes($model);
               // }

                $this->upUpdate($model);
                return $this->redirect(['view', 'id' => $model->id, 'm' => Yii::$app->request->post('m')]);
            }

        }


        $searchModel = new CdFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['status'=>1]);


        return $this->render('update', [
            'model' => $model,
            'm' => Yii::$app->request->get('m'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        /*
        return $this->render('update', [
            'model' => $model,
            //'route' => 'personal',
        ]);
        */
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
        if(!$model->resident_id) $model->delete();
        else(ExecutorAssignment::deleteAll(['job_id' => $id, 'sorcerer_id' => Resident::findOne(['user_id' => Yii::$app->user->id])->id]));

        return $this->redirect(['index', 'm' => Yii::$app->request->get('m')]);
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
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function upCreate($model){
        //сохраняем создателя работы
        $model->resident_id = Resident::findOne(['user_id' => Yii::$app->user->id])->id;

        //сохнаняем назначение в executor_assignment
        $assign = new ExecutorAssignment();
        //сохраняем создателя назначения
        $assign ->sorcerer_id = $model->resident_id;

        //сохраняем Исполнителя

        if($model->executor) {
            $assign->resident_id = $model->executor;
            //сохраняем ид работы
        }
        $assign->job_id = $model->id;
        $this->zeroStatuses($model->id);
        $assign->status = 1;
        $assign->save();
    }


    protected function upCreate2($model, $assign){
        //сохраняем создателя работы
        $model->resident_id = Resident::findOne(['user_id' => Yii::$app->user->id])->id;

        //сохнаняем назначение в executor_assignment
       // $assign = new ExecutorAssignment();
        //сохраняем создателя назначения
        $assign ->sorcerer_id = $model->resident_id;

        //сохраняем Исполнителя

        if($model->executor) {
            $assign->resident_id = $model->executor;
            //сохраняем ид работы
        }
        $assign->job_id = $model->id;
        $this->zeroStatuses($model->id);
        $assign->status = 1;
        $assign->parent_job_id = $model->idid;
        $assign->persent = $model->percent;
        $assign->save();
    }

    protected function upUpdate($model){
        //сохраняем создателя работы
        $model->resident_id = Resident::findOne(['user_id' => Yii::$app->user->id])->id;

        //сохнаняем назначение в executor_assignment
        $assign = ExecutorAssignment::find()->where(['job_id' => $model->id])->one();
        //сохраняем создателя назначения
      //  $assign ->sorcerer_id = $model->resident_id;

        //сохраняем Исполнителя

        if($model->executor) {
            $assign->resident_id = $model->executor;
            //сохраняем ид работы
        }
        $assign->job_id = $model->id;
       // $this->zeroStatuses($model->id);
        $assign->status = 1;
        $assign->save();
    }

    protected function zeroStatuses($id){
       // $assignments = ExecutorAssignment::updateAll(['status'=>0],['job_id'=>$id]);//find()->where(['job_id'=>$id])->all();
       ExecutorAssignment::deleteAll(['job_id'=>$id]);//find()->where(['job_id'=>$id])->all();


    }

    //защита от попытки взлома через подмену id персонального плана
    protected function guard($id){
        $assign = ExecutorAssignment::findOne(['job_id'=>$id]);
        $executor = Resident::findOne($assign->resident_id);
        $user_resident = Resident::findOne(['user_id'=>Yii::$app->user->id]);
        if($user_resident) {
            if ($executor->sector_id != $user_resident->sector_id) {
                return $this->redirect(['index']);
            }
        }


    }

    protected function guard2($id){
        $assign = ExecutorAssignment::findOne(['job_id'=>$id]);
        if($assign->parent_job_id) {
                return $this->redirect(['index']);
        }
    }


    //Защита от редактирования неактивной темы
    protected function guard3($id){
        
    }

    //Обработка onchange списка тем на форме плана-графика
    public function actionSetTheme($id){
        $exec_div = ExecDivAssignment::find()->select('job_id')
            ->where(['sector_id'=>Resident::findOne(['user_id'=>Yii::$app->user->id])->sector_id]);


        $jobs_done = ExecutorAssignment::find()->select('parent_job_id')->where(['persent'=>100])->andFilterWhere(['>', 'parent_job_id', 0]);//;->andFilterWhere(['!=', 'parent_job_id',null]);
        $jobs_splited = ExecutorAssignment::find()->select(['parent_job_id', 'SUM(persent) as total_percent'])
            ->where(['<', 'persent', 100])
            ->groupBy(['parent_job_id'])->asArray();

       // echo  '<option>- '.$jobs_splited->count().' -</option>';return;


        //echo '<script>alert("'.$jobs_splited->count().'")</script>';
        // ->orFilterWhere([''])

        $arr = [];
        foreach ($jobs_splited->all() as $job){
            //echo '<script>alert("'.$job['parent_job_id'].'")</script>';
            if($job['total_percent'] == 100)$arr[] = $job['parent_job_id'];
        }

        // echo '<script>alert("'.count($arr).'")</script>';


        $rows = PersonalPlan::find()->where(['in','id', $exec_div])->andFilterWhere(['theme_id'=>$id])
            ->andFilterWhere(['>','labor',0])
            ->andFilterWhere(['not in', 'id', $jobs_done])
            ->andFilterWhere(['not in', 'id', $arr])
            ->all();


        // echo '<script>alert("'.count($rows).'");</script>';


        // echo "<option>Не визначено</option>";

        //if(Yii::$app->user->id == 119) echo "<option value='1'>" . count($rows) . "</option>";return;

        if (count($rows) > 0) {
            echo "<option>- Виберіть роботу -</option>";
            foreach ($rows as $row) {
                echo "<option value='$row->id'>" . $row->content . "</option>";
            }
        }
        exit();
    }

    public function actionSetJob($id){
        $job = PersonalPlan::findOne($id);
        $used_labor = 0;

        $parent_jobs = ExecutorAssignment::find()->select('job_id')->where(['parent_job_id' => $id]);
       if($parent_jobs->count()) {
           $used_labor = PersonalPlan::find()->where(['in', 'id', $parent_jobs])->sum('labor');
           echo $job->labor - $used_labor;
       }else {
           echo $job->labor;
       }
    }

    public function actionSetPercent($id){

        $used_percents = ExecutorAssignment::find()->where(['parent_job_id' => $id])->sum('persent');
        echo 100-$used_percents;

    }

    public function actionSetPart($id, $percent){
        $job = PersonalPlan::findOne($id);
        echo $job->labor/100*$percent;
    }

    public function actionSetProgress($id, $percent, $labor, $started_at, $finished_at){
        if(!$started_at)return;
        $d = explode('-',$started_at);
        echo Basic::getZagruskaHtml($id, $d[1], $labor, $percent);
    }

//добавить связи работы с элементами дерева
    protected function saveNodes($model){
        $nodes_arr = explode(',', $model->nodes);
        JobNode::deleteAll(['job_id'=>$model->id]);
        foreach ($nodes_arr as $node){
            $jn = new JobNode();
            $jn->job_id = $model->id;
            $jn->node_id = $node;
            $jn->save();
        }
    }

//загрузить в модель связи работы с элементами дерева
    protected function restoreNodes(&$model){

        if($nodes = JobNode::findAll(['job_id' => $model->id])) {
            $str = '';
            foreach ($nodes as $node) {
                $str .= $node->node_id . ',';
            }
            $model->nodes = substr($str,0,-1);
        }
    }

//обработать ончендж виджета дерева
    public function actionSetFilesTable($nodes){
        //echo $nodes;
        return $this->renderAjax('files_table', [
            'nodes' => $nodes,
            ]);
    }
}
