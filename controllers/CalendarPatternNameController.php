<?php

namespace frontend\controllers;

use frontend\models\CalendarPattern;
use frontend\models\Resident;
use Yii;
use frontend\models\CalendarPatternName;
use frontend\models\CalendarPatternNameSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CalendarPatternNameController implements the CRUD actions for CalendarPatternName model.
 */
class CalendarPatternNameController extends Controller
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
     * Lists all CalendarPatternName models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CalendarPatternNameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CalendarPatternName model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CalendarPatternName model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CalendarPatternName();

        if ($model->load(Yii::$app->request->post())) {
            $model->author_id = Resident::findOne(['user_id'=>Yii::$app->user->id])->id;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => CalendarPattern::find()->
                andWhere(['name_id' => null])
                ,'pagination' => false,])
        ]);
    }

    /**
     * Updates an existing CalendarPatternName model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!$this->isAuthor($model->author_id)){
            return $this->redirect(['index']);
        }
       // $patternsSearch = CalendarPattern::find()->where(['name_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => CalendarPattern::find()->
            andWhere(['name_id' => $id])
        ,'pagination' => false,]);



        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing CalendarPatternName model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!$this->isAuthor($model->author_id)){
            return $this->redirect(['index']);
        }

        CalendarPattern::deleteAll(['name_id'=>$id]);

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CalendarPatternName model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CalendarPatternName the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CalendarPatternName::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionClick($date, $act, $color, $name_id){

        //echo '<script>alert("'.$name_id.'")</script>'; exit();
       // return;
        \frontend\models\CalendarPattern::deleteAll(['start_date'=>$date,
           // 'color' => $color,
            'name_id'=>$name_id,
        ]);
        if($act){
            $data = new CalendarPattern();
            $data->active = 1;
            $data->start_date = $date;
            $data->end_date = $date;
            $data->color = $color;

            $data->name_id = $name_id;
            $data->save();
        }

    }

    protected function isAuthor($author_id){
        if($author_id == Resident::findOne(['user_id'=>Yii::$app->user->id])->id)return true;
        return false;
    }
}
