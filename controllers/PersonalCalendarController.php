<?php

namespace frontend\controllers;

use frontend\models\CalendarPattern;
use frontend\models\Conference;
use frontend\models\Resident;
use Yii;
use frontend\models\PersonalCalendar;
use frontend\models\PersonalCalendarSearch;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Calendar2;

/**
 * PersonalCalendarController implements the CRUD actions for PersonalCalendar model.
 */
class PersonalCalendarController extends Controller
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
     * Lists all PersonalCalendar models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }

        if(!Yii::$app->user->can('setCalendar')){
            return $this->redirect(['site/login']);
        }


        $calendar = new Calendar2();

        $calendar->load(Yii::$app->request->post());

        if($calendar->use){
            $this->setPersonalCalendar($calendar->resident_id, $calendar->use);
        };




        $dataProvider = new ActiveDataProvider([
        'query' => CalendarPattern::find()->Where(['name_id' => null])
        ,'pagination' => false,

    ]);





        if($calendar->resident_id) {
            $dataProvider = new ActiveDataProvider([
                'query' => Conference::find()->
                andWhere(['active' => 1, 'resident_id' => $calendar->resident_id])
              //  ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
                //   ->orWhere(['resident_id' => null])
                , 'pagination' => false,

            ]);
        }else if($calendar->pattern_id2){
            $dataProvider = new ActiveDataProvider([
                'query' => CalendarPattern::find()->
                andWhere(['name_id' => $calendar->pattern_id2])
                //   ->orWhere(['resident_id' => null])
                ,'pagination' => false,

            ]);
        }




        return $this->render('index',
            [
                'dataProvider' => $dataProvider,
              //  'dataProvider2' => $dataProvider2,
                //'calendar2' => $calendar2,
                'calendar' => $calendar,

            ]
        );

    }

    /**
     * Displays a single PersonalCalendar model.
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
     * Creates a new PersonalCalendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PersonalCalendar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PersonalCalendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PersonalCalendar model.
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
     * Finds the PersonalCalendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonalCalendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PersonalCalendar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionClick($date, $act, $color, $resident_id = null){
       // echo '<script>alert("'.$color.' - '.$act.'")</script>';

        //return; //lb
        //if(!$resident_id)return;

             Conference::deleteAll(['start_date'=>$date,
               // 'color' => $color,
                'resident_id'=>$resident_id,
            ]);


        if($act){
            //echo '<script>alert("'.$color.'")</script>';
            $data = new Conference();
            $data->active = 1;
            $data->start_date = $date;
            $data->end_date = $date;
            $data->color = $color;
          //  if($act == 2){
         //       $data->resident_id = null;
         //   }else {
                $data->resident_id = $resident_id;
         //   }
            $data->save();
        }
        // $this->redirect(['site/test1']);
        // return $this->actionTest1();
    }

    protected function setPersonalCalendar($resident_id, $pattern_id){

       // echo '<script>alert("'.$from_now.'")</script>';exit();

        Conference::deleteAll(['resident_id' => $resident_id]);

        $source = CalendarPattern::find()->where(['name_id'=>$pattern_id]);

        $query = "INSERT INTO `conference` (`active`, `start_date`, `end_date`, `color`, `resident_id`) VALUES ";

        if(!$source)return;
        $n = $source->count();
        if($n < 2)return;
        foreach ($source->all() as $key => $rec){
            $query .= "(1, '".$rec->start_date."', '".$rec->end_date."', '". $rec->color ."', $resident_id)";
            if($key < $n-1)$query .= ', ';
            //else $query .= '';
        }


        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($query);
       //var_dump($query);exit();

        $command->query();
    }


    public function actionCode($id){
        //echo '<script>alert("'.$id.'")</script>';
        $code = explode('-', $id);
        echo '<td><div style="width: 25px; height: 25px; background-color: '.$code[1].'">'.'&nbsp;'.'</div><td>&nbsp;'.'</td>';
    }

    public function actionClickr($color){
        echo $color;
    }
}
