<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\Div;
use frontend\models\Phone;
use frontend\models\Sectors;
use Yii;
use frontend\models\Resident;
use frontend\models\ResidentSearch;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ResidentController implements the CRUD actions for Resident model.
 */
class ResidentController extends Controller
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
     * Lists all Resident models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResidentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Resident model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->struct_name = $model->struct->name;
        $model->div_name = $model->div->name;

        $model->sector_name = $model->sector->name;
        $role = yii::$app->authManager->getRole($model->posada_name);
        if($role) {
            $model->posada_desc = $role->description;
        }else {
            $model->posada_desc = null;
        }
        $user = User::findOne(['id'=>$model->user_id]);
      //  if($user){
        if($model->photo) {
            $photo = $model->path . $model->photo;
        }
        else {
            $photo = $model->path.'inkognito.png';
        }
      //  }else {
     //       $photo = $model->path.'inkognito.png';
     //   }
        $model->file = '<img src="'.$photo.'" class="photo-face"/>';
        if($user){
            $model->user_name = $user->username;
        }else $model->user_name = '';

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Resident model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('editResidents'))return $this->redirect(['index']);

        $model = new Resident();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Resident model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->user->can('editResidents'))return $this->redirect(['index']);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if($image = UploadedFile::getInstance($model, 'image')) {

                $ext = explode(".", $image->name)[1];
                $photo = $image->name;
                //$model->photo = Yii::$app->security->generateRandomString().".{$ext}";
                $model->photo = $model->id . '-' . $model->fname . '-' . $model->sname . ".{$ext}";
                $path = 'images/residents/' . $model->photo;
                $image->saveAs($path);
            }

            $model->save();
            $this->savePhones($model);
            if($model->user_id) {
            //    var_dump($model->posada_name); var_dump($model->user_id);exit();
              //  $role = Yii::$app->authManager->getRole($model->posada_name);
                //var_dump(Yii::$app->authManager->getRolesByUser($model->user_id)); exit();
                Yii::$app->authManager->revokeAll($model->user_id);
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->posada_name), $model->user_id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Resident model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Resident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Resident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resident::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionSetdiv($id){
        $rows = Div::find()->where(['struct_id' => $id])->all();

        echo "<option>-Виберіть підрозділ-</option>";

        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id'>$row->name</option>";
            }
        }
        else{
            echo "<option>пусто</option>";
        }
    }



    public function actionSetsector($id){
        $rows = Sectors::find()->where(['div_id' => $id])->all();

        echo "<option>-Виберіть сектор-</option>";

        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id'>$row->name</option>";
            }
        }
        else{
            echo "<option>пусто</option>";
        }
    }

    public function actionUpload($id){
        echo '<img src="images/residents/logo6.png" alt="'.$id.'"/>';
        $fname = explode('fakepath',$id)[1];

        echo '<script>
                $("#resident-photo").val("'.$fname.'");
                </script>';


    }


    protected function savePhones($model)
    {

        //var_dump($model->phones);
        //exit();
        /*

        $cond = //[ 'and',
            ['in', 'id', $model->phones];
        */
        //];

       // if(sizeof($model->phones) > 0){
         //   Phone::deleteAll(['resident_id' => $model->id]);
     //   }
      //  foreach ($model->phones as $phone){
     //       $rec = new Phone();
     //       $rec->resident_id = $model->id;
     //       $rec->number = $phone['number'];
     //       $rec->save();
     //   }
       // Phone::updateAll(['resident_id' => $model->id, 'type' => 1], $cond);

        //Phone::updateAll(['resident_id' => $model->id]);
        if(!$model->phones) return;
        $phone = Phone::findOne(['resident_id' => $model->id]);
        if($phone) {
            $phone->number = $model->phones;
            $phone->save();
        }else{
            $phone = new Phone();
            $phone->resident_id = $model->id;
            $phone->number = $model->phones;
            $phone->save();
        }
    }


}
