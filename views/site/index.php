
<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;


//if(Yii::$app->user->can('system') ){
//if(\frontend\models\Resident::findOne())

//Блок сообщения о днях рождения

if (!Yii::$app->user->isGuest) {
    echo '<div id="message2">';

    $bd_residents = \frontend\models\Resident::find()->where(['DATE_FORMAT(dob, "%m-%d")' => Date('m-d')]);
    $bdt_residents = \frontend\models\Resident::find()->where(['DATE_FORMAT(dob, "%m-%d")' => date('m-d', strtotime("+1 day"))]);
    $message = '';
    $message_t = '';
    if ($bd_residents->count()) {
        $message .= '<b>День народження сьогодні у:';
        foreach ($bd_residents->all() as $resident) {
            $message .= Html::a($resident->fname . ' ' . $resident->sname, ['resident/view', 'id' => $resident->id],
                ['style' => 'margin: 10px']);
        }
        $message .= '</b>';
    }
    if ($bdt_residents->count()) {

        $message_t .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; День народження завтра у:';
        foreach ($bdt_residents->all() as $resident) {
            $message_t .= Html::a($resident->fname . ' ' . $resident->sname, ['resident/view', 'id' => $resident->id],
                ['style' => 'margin: 10px']);
        }
    }
    if ($message_t || $message) {
        Yii::$app->session->setFlash('success', $message . $message_t);
    }
    echo '</div>';
}
//}


$this->title = 'Профіль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <?php
    //$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

        if(!Yii::$app->user->isGuest) {
            //echo '<div>';
           // echo $form->field($model, 'imageFile')->fileInput(['class'=>'col-lg-3', 'title'=>'d']);

            if($model->user_id && $model->photo) {
                echo '<img src="' .$model->path.$model->photo . '" class="col-lg-3 photo-face">';
            }else{
                echo '<img src="images/residents/inkognito.png" class="col-lg-3 photo-face">';
            }

      //      ActiveForm::end();
        //    echo '</div>';

            echo '<div class="col-lg-offset-3">';
            if($model->user_id !== null) {
                echo '<h4>Вашi особисті дані</h4>' . \yii\widgets\DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                //  'id',
                                'sname',
                                'fname',
                                'lname',
                                'posada_desc',
                                'tab',
                                //'dob',
                            ],
                        ]);

                echo '<h4>Розташування</h4>' . \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [

                        'struct_name',
                        'div_name',
                        'sector_name',
                    ],
                ]);

            }else{
                echo '<h4 class="red-text">Цей акаунт не підтвержено</h4>';
            }
            echo '</div>';
        /*
        echo "<b>Призвище:</b> <br>";
        echo "Ім'я: <b>".$model->fname."</b> <br>";
        echo "<b>По батькові:</b> <br>";
        echo "<b>Табельний №:</b> <br>";
        echo '<br>';
        echo "<b>Структура:</b> <br>";
        echo "<b>Відділ:</b> <br>";
        echo "<b>Сектор:</b> <br>";
*/
            if(array_key_exists('Конструктор', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
                //echo "<h4>Програми розробки КД:</h4>";
                echo '<hr>';
            }



            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
            //if($roles) {
            echo '<h4>Ролі у системі:</h4>';

            foreach ($roles as $role) {

                //echo '<b>' . $role->name . '</b> (' . $role->description . ')<br>';

                $role_name = $role->name;//array_keys($role)[0];

                $child_roles = Yii::$app->authManager->getChildRoles($role_name);


                foreach ($child_roles as $c) {
                    echo '<b>' . $c->name . '</b> (' . $c->description . ')<br>';

                }

            }

            echo '<hr><h4>Дозволи:</h4>';
            $permissions = Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->id);
            foreach ($permissions as $perm) {
                echo $perm->description . '<br>';
            }
        //}else {
          //  echo '<h4 class="red-text">Посада не призначена</h4>';

     //   echo '</b>';
    }

  //  if (Yii::$app->user->can('uploadFiles')){
  //      echo '<h3>Разрешено</h3>';
  //  }else {
  //      echo '<h3>Запрещено</h3>';
  //  }

    ?>


</div>
