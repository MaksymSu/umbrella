<?php

namespace frontend\controllers;

use frontend\models\Reports\Workers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * SectorsController implements the CRUD actions for Sectors model.
 */
class WorkersController extends Controller
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
     * Lists all Sectors models.
     * @return mixed
     */
    public function actionIndex()
    {


        return $this->render('index', [

            'model' => new Workers(),
        ]);
    }

    public function actionSetMonth($id){
        return $this->renderPartial('report', [
            'm' => str_pad($id+1, 2, '0', STR_PAD_LEFT),
        ]);
    }
}