<?php

namespace frontend\controllers;

use Yii;
use frontend\models\MainTree;
use frontend\models\MainTreeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MainTreeController implements the CRUD actions for MainTree model.
 */
class MainTreeController extends Controller
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

    public function actionIndex($theme_id = false)
    {
        $model = new MainTree();
        $model->load(Yii::$app->request->post());
        $model->theme_id = $theme_id;
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionSetAvatar($node){

    }
}
