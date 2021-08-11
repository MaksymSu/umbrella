<?php

namespace frontend\controllers;

use frontend\models\CdFiles;
use frontend\models\CdFilesSearch;
use frontend\models\ExecutorAssignment;
use frontend\models\Resident;
use frontend\models\Tree;
use Yii;
use frontend\models\PersonalPlan;
use frontend\models\PersonalPlanSearch;
use frontend\models\PersonalPlanSearchSector;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use frontend\models\JobNode;

/**
 * PersonalPlanController implements the CRUD actions for PersonalPlan model.
 */
class PersonalPlanController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
        $searchModel->m = Yii::$app->request->get('m');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        //   }
    }

    public function actionIndexs(){
        // if(Yii::$app->user->can('taskForSector')) {
        if(!Yii::$app->user->can('taskForSector')){
            $this->redirect(['site/error']);
        }
        $searchModel = new PersonalPlanSearchSector();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('indexs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        //  }else{
    }

    /**
     * Displays a single PersonalPlan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->guard($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PersonalPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('taskForSector')){
            $this->redirect(['site/error']);
        }
        $model = new PersonalPlan();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()) {
                $this->saveNodes($model);
                return $this->redirect(['view', 'id' => $model->id, 'm' => Yii::$app->request->post('m')]);
            }
          //  $this->up($model);
        }

        return $this->render('create', [
            'model' => $model,
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
        if($model->status ==2){
            return $this->redirect(['index']);
        }


        //защита от попытки взлома через подмену id персонального плана
        $this->guard($model->id);



        if ($model->load(Yii::$app->request->post())) {

           // $this->up($model);

            if($model->save()) {

                    $this->saveNodes($model);

                return $this->redirect(['view', 'id' => $model->id, 'm' => Yii::$app->request->post('m')]);
            }
        }

     //   if(Yii::$app->user->can('system')) { //lb
            $this->restoreNodes($model);
    //    }



        $searchModel = new CdFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('update', [
            'model' => $model,
            'm' => Yii::$app->request->get('m'),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing PersonalPlan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
*/
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

    protected function zeroStatuses($id){
        $assignments = ExecutorAssignment::updateAll(['status'=>0],['job_id'=>$id]);//find()->where(['job_id'=>$id])->all();

    }

    protected function up(&$model){
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
            $assign->job_id = $model->id;
            $this->zeroStatuses($model->id);
            $assign->status = 1;
            $assign->save();
        }
    }

//защита от попытки взлома через подмену id персонального плана
    protected function guard($id){
        $assign = ExecutorAssignment::findOne(['job_id'=>$id]);
        /////!!!!!!
        if(!$assign)return;

        $executor = Resident::findOne($assign->resident_id);

        if($executor->user_id != Yii::$app->user->id){
            return $this->redirect(['index']);
        }
    }

//добавить связи работы с элементами дерева
    protected function saveNodes($model){
        $nodes_arr = explode(',', $model->nodes);
        JobNode::deleteAll(['job_id'=>$model->id]);

        if(!sizeof($nodes_arr))return;
        foreach ($nodes_arr as $node){
            $jn = new JobNode();
            $jn->job_id = $model->id;
            $jn->node_id = $node;
            $jn->save();
        }
        Tree::updateAll(['removable'=>0],['in', 'id', $nodes_arr]);
        $this->setStatuses($nodes_arr, $model->id);
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
    public function actionSetFilesTable($nodes, $job_id){
        //echo $nodes;

        $searchModel = new CdFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->renderAjax('files_table', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'job_id' => $job_id,
            'nodes' => $nodes,

        ]);

/*
        return $this->renderAjax('files_table', [
            'nodes' => $nodes,
            'job_id' => $job_id,
        ]);
*/
    }

//добовить список елементов в дропдаун
    public function actionSetElementsDropdown($nodes){
        $rows = explode(',', $nodes);
        if(count($rows)>0){
            $nodes_objs = Tree::find()->where(['in','id',$rows])->all();

            foreach($nodes_objs as $row){
                echo "<option value='$row->id'>".$row->name." (".$row->N.")</option>";
            }
        }
    }

    //бработать загрузку файла
    public function actionUpload(){
        $post = Yii::$app->request->post();
      // file_put_contents('file_data2.txt', json_encode($_FILES['attachment_1']).' > '.$post['job_id']);

       // CdFiles::updateAll(['resident_id' => 110]);
        $db_file = new CdFiles();
        $db_file->status = 5;
        $db_file->save(false);
        $db_file->job_id = $post['job_id'];
        $db_file->node_id = $post['node_id'];
        $db_file->source_id = $post['system_id'];
        $db_file->resident_id = Resident::findOne(['user_id'=>Yii::$app->user->id])->id;

        // file_put_contents('file_cur.txt', $_FILES['attachment_1']['name'][0]);
        $file_name_arr = explode('.',$_FILES['attachment_1']['name'][0]);

        //file_put_contents('file_data4.txt', count($file_name_arr));

        if($size =count($file_name_arr) > 1)$ext = '.'.end($file_name_arr);//$ext = $file_name_arr[$size];
        else $ext = '.null';

        $db_file->sys_name =
            //$post['node_id'].'_'.$post['job_id'].'_'.$post['system_id'].'_'.$db_file->resident_id.'_'
            $db_file->id.'_'
            .Tree::findOne($post['node_id'])->N
            .$ext;


        $db_file->user_name = $_FILES['attachment_1']['name'][0];


        $path = Yii::getAlias('@webroot') . '/cd/tree/';

      //  return is_uploaded_file($_FILES['attachment_1']['tmp_name'][0]);
        $res = move_uploaded_file($_FILES['attachment_1']['tmp_name'][0], $path . $db_file->sys_name);
        if($res) {
            $db_file->status = 2;
            $db_file->save(false);
        return true;
        }

        $db_file->delete();
         return false;
    }

/*
    public function actionFilerFiles(){

        $model = new CdFiles();
        $searchModel = new CdFilesSearch();
        if ($model->load(Yii::$app->request->post()) ) {

            $dataProvider = $searchModel->search( [ 'searchModel'=> ['id' => $model->id]]);
        }


        return $this->render('files_table', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
          //  'job_id' => $job_id,

        ]);
    }
*/


    public function actionDownload($id){
        $path = Yii::getAlias('@webroot') . '/cd/tree/';
        $file = $path . CdFiles::findOne($id)->sys_name;
        if (file_exists($file)) {
           return Yii::$app->response->sendFile($file);
        }
        return false;
    }

    public function actionDeleteFile($id, $job_id, $nodes){
        $file = CdFiles::findOne($id);
        $file->delete();
        $path = Yii::getAlias('@webroot') . '/cd/tree/';

        unlink($path.$file->sys_name);

        //CdFiles::deleteAll(['id'=>$id]);


        $searchModel = new CdFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       // $nodes=null;
        return $this->renderAjax('files_table', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'job_id' => $job_id,
            'nodes' => $nodes,

        ]);
    }


    public function setStatuses($nodes_arr, $job_id){
        CdFiles::updateAll(['status' => 1],['and', ['job_id'=>$job_id], ['in', 'node_id', $nodes_arr]]);

    }
}
