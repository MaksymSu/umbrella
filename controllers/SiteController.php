<?php
namespace frontend\controllers;

use Faker\Provider\DateTime;
use frontend\models\Basic;
use frontend\models\CalendarReport;
use frontend\models\Conference;
use frontend\models\Div;
use frontend\models\Norm;
use frontend\models\Resident;
use frontend\models\Sectors;
use tecnocen\yearcalendar\widgets\ActiveCalendar;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Calendar2;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
   // public $layout;


    public $layout = 'sidenav';

    public function drawColors(){
        return
        '<div id="colors"></div>';
    }


    public function behaviors()
    {
        if(Yii::$app->user->can('system')){
            file_put_contents('admin_log_2.txt', $_SERVER['REMOTE_ADDR'].
                ' -- '.date('Y-m-d h:i:s')."\n", FILE_APPEND);
        }

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionCalendarSet(){
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }

        $models = Conference::find()->Where(['active' => 2, 'resident_id' => null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $models,
            'pagination' => false,
        ]);


        return $this->render('calendarSet',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionCalendar($slave_id = null){
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }
        $calendar2 = new Calendar2();
        $calendar2->load(Yii::$app->request->post());

        $dataProvider = new ActiveDataProvider([
            'query' => Conference::find()->
            andWhere(['active' => 1, 'resident_id' => Resident::findOne(['user_id' => Yii::$app->user->id])->id])
                //->orWhere(['resident_id' => null])
            ,
            'pagination' => false,

        ]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => Conference::find()->
            andWhere(['active' => 1, 'resident_id' => $calendar2->resident_id])
               // ->orWhere(['resident_id' => null])
            ,
            'pagination' => false,

        ]);







        return $this->render('calendar',
            [
                'dataProvider' => $dataProvider,
                'dataProvider2' => $dataProvider2,
                'calendar2' => $calendar2,

            ]
        );
    }


    public function actionCalendarSlave($resident_id){

       // echo '<script>alert("'.$resident_id.'")</script>';return;
        $dataProvider = new ActiveDataProvider([
            'query' => Conference::find()->
            andWhere(['active' => 1, 'resident_id' => 110])
               // ->orWhere(['resident_id' => null])
            ,'pagination' => false,

        ]);

         $year = date('Y');
        echo  \tecnocen\yearcalendar\widgets\ActiveCalendar::widget([
            'language' => 'uk',
            'dataProvider' => $dataProvider,
            'options' => [
                'id' => 'uk-calendar2',
            ],
            'clientOptions' => [
                'contextMenuItems' => true,
                'style' => 'background',
                'enableRangeSelection' => true,
                'enableContextMenu' => true,

                'startYear'=> $year,
                'minDate'=> new \yii\web\JsExpression('new Date("'.($year-1).'-12-31")'),
                'maxDate'=> new \yii\web\JsExpression('new Date("'.$year.'-12-31")'),
            ],

        ]);


    }

    public function actionClick($date, $act, $color){

       // echo '<script>alert("'.$color.'")</script>';
    if(!$act){

        $resident_id = null;

        if($color != "#bdf") {
            $resident_id = Resident::findOne(['user_id' => Yii::$app->user->id])->id;
        }
        \frontend\models\Conference::deleteAll(['start_date'=>$date,
            'color' => $color,
            'resident_id'=>$resident_id,
        ]);
    }else {
        $data = new \frontend\models\Conference();
        $data->active = $act;
        $data->start_date = $date;
        $data->end_date = $date;
        $data->color = $color;
        if($act == 2){
            $data->resident_id = null;
        }else {
            $data->resident_id = Resident::findOne(['user_id' => Yii::$app->user->id])->id;
        }
        $data->save();
    }
       // $this->redirect(['site/test1']);
       // return $this->actionTest1();
    }



    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }

        if(($model = Resident::findOne(['user_id' => yii::$app->user->id])) !== null) {
            //if (($model = Resident::findOne($id)) !== null)
            $model->struct_name = $model->struct->name;
            $model->div_name = $model->div->name;
            $model->sector_name = $model->sector->name;
            if($role = yii::$app->authManager->getRole($model->posada_name)) {
                $model->posada_desc =$role->description;
            }else{
                $model->posada_desc = 'Не визначено';
            }
        }else{
            $model = new Resident();
        }
        $this->layout = 'sidenav';
        return $this->render('index',
            ['model' => $model]
            );
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->redirect(['site/login']);
        //return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    public function actionNews(){
        return $this->render('news', [
        ]);
    }



    public function actionCalendarReport(){
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }

        $model = new \frontend\models\Reports\CalendarReport();
        $model->load(Yii::$app->request->post());
        return $this->render('report/calendar-report', [
            'model' => $model,
        ]);
    }

///// Рапорт по графикам
    public function actionCalendarReportAll(){
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }


        $model = new \frontend\models\Reports\CalendarReportAll();
        $model->load(Yii::$app->request->post());
        return $this->render('report/calendar-report-all', [
            'model' => $model,
        ]);
    }

    public function actionSetStruct($id){
        $rows = Div::find()->where(['struct_id' => $id])->all();

        echo "<option>- Всі -</option>";

        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id'>$row->name</option>";
            }
        }

    }

    public function actionSetDiv($id){
        $rows = Resident::find()->where(['div_id' => $id, 'type' => 0])->orderBy(['sname' => SORT_ASC])->all();

      //  echo "<option>- Всі -</option>";

        if(count($rows)>0){
            foreach($rows as $row){
//                echo "<option value='$row->id'>$row->name</option>";
                echo "<option value='$row->id'>".$row->sname." ".$row->fname." ".$row->lname."</option>";

            }
        }

    }

    public function actionSetSector($id){
        $rows = Resident::find()->where(['sector_id' => $id])->all();

        echo "<option>- Всі -</option>";

        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id'>".$row->sname." ".$row->fname." ".$row->lname."</option>";
            }
        }

    }
    ////////////// end

    public function actionWeekReport(){
        //$model = new \frontend\models\Reports\CalendarReport();
        //$model->load(Yii::$app->request->post());
        return $this->render('report/week-report', [
            //'model' => $model,
        ]);
    }

    public function actionSetdays($month, $year, $day = false){
        //echo '<script>alert("'.$day.'")</script>';

        $rows = Basic::getDaysInMonth($year, $month);
        echo "<option>- Кінець місяця -</option>";
        if(count($rows)>0){
            foreach($rows as $row){
                if((int)$row == (int)$day)$s='selected';
                else $s='';
                echo "<option value='$row' $s>".$row."</option>";
            }
        }

    }

    public function actionTest(){
        return $this->render('test1', [
        ]);
    }


}
