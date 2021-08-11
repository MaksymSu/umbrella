<?php

namespace frontend\controllers;

use frontend\models\Norm;
use Yii;
use frontend\models\NormNameInput;
use frontend\models\NormNameInputSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NormNameInputController implements the CRUD actions for NormNameInput model.
 */
class NormNameInputController extends Controller
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
     * Lists all NormNameInput models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NormNameInputSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NormNameInput model.
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
     * Creates a new NormNameInput model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NormNameInput();

        if ($model->load(Yii::$app->request->post())) {

            $model->save();
            $this->newSet($model->id);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing NormNameInput model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Norm::find()->where(['name_id' => $id])->count() < 30){
            //echo ' :)';
            //exit();
            $this->newSet($id);
        }

        $nov_arr = Norm::getNovelties();
        $dif_arr = Norm::getDifficulties();

        if ($model->load(Yii::$app->request->post())) {

            $novs = explode("\n", $model->variants);
            foreach ($novs as $nov_key=>$rec){
                $difs = explode(' ', $rec);
                foreach ($difs as $dif_key=>$v){
                    if($dif_key >5)continue;
                    $norm = $this->findNorm($model->id, $nov_arr[$nov_key], $dif_arr[$dif_key]);
                    $norm->value = $v;
                    $norm->save();
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }



        foreach ($nov_arr as $nov_key=>$rec){
            foreach ($dif_arr as $dif_key=>$v){
                $norm = $this->findNorm($model->id, $nov_arr[$nov_key], $dif_arr[$dif_key]);
                $model->variants .= $norm->value;
                if($dif_key <5)$model->variants .= ' ';
                else break;
            }
            if($nov_key <4)$model->variants .= "\n";
            else break;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing NormNameInput model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Norm::deleteAll(['name_id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the NormNameInput model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NormNameInput the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NormNameInput::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function findNorm($name_id, $nov, $dif)
    {
       return Norm::findOne(['name_id' => $name_id, 'novelty' => $nov, 'difficulty' => $dif]);

    }


    protected function newSet($id){
        Norm::deleteAll(['name_id' => $id]);

        $nov_arr = Norm::getNovelties();
        $dif_arr = Norm::getDifficulties();
        foreach ($nov_arr as $nov_key=>$rec){
            foreach ($dif_arr as $dif_key=>$v){
                $norm = new Norm();
                $norm->name_id = $id;
                $norm->novelty = $rec;
                $norm->difficulty = $v;
                $norm->value=0;
                $norm->save();
            }
        }
    }
}
