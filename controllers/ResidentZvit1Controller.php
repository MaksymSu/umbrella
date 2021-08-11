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
class ResidentZvit1Controller extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $layout = 'sidenav';

    public function behaviors()
    {
        if (Yii::$app->user->isGuest) {
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
       // $searchModel = new ResidentSearch();
      //  $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
          //  'searchModel' => $searchModel,
          //  'dataProvider' => $dataProvider,
        ]);
    }
}