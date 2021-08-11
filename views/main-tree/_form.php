<?php
use yii\helpers\Html;
use yii\grid\GridView;


echo $form->field($node, 'N')->textInput();

$resident = frontend\models\Resident::findOne($node->resident_id);
if(!$resident){
    $resident = \frontend\models\Resident::findOne(['user_id'=>Yii::$app->user->id]);
}

$author = $resident->sname.' '.$resident->fname.' '.$resident->lname.', '.$resident->div->name.', '.$resident->sector->name;
$node->resident_id = $resident->id;
//echo $form->field($node, 'resident_id')->textInput(['disabled' => true]);
?>
<?php

/*
echo $form->field($node, 'avatar')->widget(\kartik\file\FileInput::classname(), [
    'options' => ['accept' => 'image/*'],
    'pluginOptions' => [
        'name' => 'attachment_avatar',
        'showUpload' => false,
        'browseLabel' => '',
        'removeLabel' => '',
        // 'mainClass' => 'input-group-lg'
        'showPreview' => false,
    ]
]);
*/


/*
echo \kartik\file\FileInput::widget([
    'name' => 'attachment_51',
    'pluginOptions' => [
        'showUpload' => false,
        'browseLabel' => '',
        'removeLabel' => '',
       // 'mainClass' => 'input-group-lg'
        'showPreview' => false,
    ]
]);
*/
?>

<?php

echo '<b>Елемент добавив</b>';
echo '<div class="form-inline">';
//echo Html::input('text', 'resident', $author, ['disabled' => true, 'class' => 'form-control', 'style' => 'width: 70%']);

echo $author;
echo Html::a('Профіль', ['/resident/view', 'id' => $node->resident_id],
    ['class' => 'btn btn-primary', 'style' => 'margin-left: 10px', 'target' => '_blank']);
echo '</div>';


if(!$node->theme_id)
$node->theme_id = Yii::$app->request->get('theme_id');




if($node->id) {


    echo '<div><b>Всі роботи по елементу:</b>';

    echo '<table class="table table-striped table-bordered detail-view">';
    echo '<tbody>';
    echo '
<tr>
        <th>Зміст</th>
        <th>Тема</th>
        <th>Виконавець</th>
        <th>Видав</th>
        <th>Видано / Виконано</th>
    </tr>

';
    $nodes2 = \frontend\models\JobNode::find()->where(['node_id' => $node->id])->andWhere(['not', ['job_id' => null]])->all(); // Тут нужно проверить есть ли файлы в этой ра
    foreach ($nodes2 as $node2) {
        if ($color = \frontend\models\PersonalPlan::getJobColor($node2->job->status)) {
            $style = "background-color: $color";
        } else {
            $style = '';
        }
        echo '<tr style="' . $style . '">';
        // echo '<td>'.$node2->job->content.'</td>';
        if (Yii::$app->user->can('viewFact')) {
            echo '<td><b>' . Html::a($node2->job->content, ['/personal-plan-main/view', 'id' => $node2->job->id],
                    ['class' => '', 'target' => '_blank']) . '</b></td>';
        } else {
            echo '<td>' . $node2->job->content . '</td>';
        }

        echo '<td>'.$node2->job->theme->number.'</td>';

        // echo '<td>'.$node2->job->started_at.' / '. $node2->job->finished_at_fact.'</td>';
        $jobbb = \frontend\models\ExecutorAssignment::findOne(['job_id' => $node2->job->id]);
        $executor = \frontend\models\Resident::findOne($jobbb->resident_id);
        $sourcer = \frontend\models\Resident::findOne($jobbb->sorcerer_id);

        echo '<td>' . $executor->sname . '.' . mb_substr($executor->fname, 0, 1) . '.' . mb_substr($executor->lname, 0, 1) . '.</td>';
        echo '<td>' . $sourcer->sname . '.' . mb_substr($sourcer->fname, 0, 1) . '.' . mb_substr($sourcer->lname, 0, 1) . '.</td>';

        echo '<td>' . $node2->job->started_at . ' / ' . $node2->job->finished_at_fact . '</td>';

        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<div>';
}


//echo $form->field($node, 'theme_id')->textInput();
echo $form->field($node, 'theme_id')->hiddenInput()->label(false);



$script = <<< JS

$("#tree-name").prop('disabled', true);
$("#tree-n").prop('disabled', true);
$(".kv-remove").prop('disabled', true);
$(".kv-move-up").prop('disabled', true);
$(".kv-move-down").prop('disabled', true);
$(".kv-move-left").prop('disabled', true);
$(".kv-move-right").prop('disabled', true);
$(':input[type="submit"]').prop('disabled', true);
$(':input[type="reset"]').prop('disabled', true);
JS;

if(\frontend\models\Resident::findOne(['user_id'=>Yii::$app->user->id])->id != $node->resident_id)
$this->registerJs($script, yii\web\View::POS_END);



echo '<div>';
echo '<details>';


    $searchModel = new \frontend\models\CdFilesSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andFilterWhere(['status'=>1])->andWhere(['node_id'=>$node->id]);




    if(Yii::$app->user->can('viewFact')) {
        $dataProvider->query->orFilterWhere(['node_id' => $node->id]);
    }

echo '<summary><b>Файли ('.$dataProvider->query->count().')</b></summary>';

    /*
if(Yii::$app->user->can('system')){
    $files = \frontend\models\CdFiles::find()->where(['node_id' => $node->id]);
    echo '<b>Файли adm('.$files->count().')</b>';
}
*/
    require_once ('files_table_view.php');

echo '</details>';
echo '</div>';