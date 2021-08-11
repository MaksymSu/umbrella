

<table class="table table-striped table-bordered">
    <tbody>

    <tr>
        <th>Файл</th>
        <th><?=$searchModel->attributeLabels()['source_id']?></th>
        <th></th>
    </tr>

    <?php
    $style_back ='';
    foreach ($dataProvider->query->all() as $rec){
        $node = \frontend\models\Tree::findOne($rec['node_id']);
        if($node){
            $node_name = $node->name;
            $node_N = $node->N;
        }else{
            $node_name = null;
            $node_N = null;
        }

        if($job = frontend\models\PersonalPlan::findOne($rec['job_id'])){
            if ($color = \frontend\models\PersonalPlan::getJobColor(\frontend\models\PersonalPlan::findOne($rec['job_id'])->status)) {
                $style_back = "background-color: $color";
            } else {
                $style_back = '';
            }
        }
/*
if($job = frontend\models\PersonalPlan::findOne($rec['job_id'])){
            if ($job->status != 2) {
                continue;
            }
        }
        */
        ?>




        <tr>
            <td style="<?= $style_back ?>"><?=$rec['sys_name']?></td>
            <td><?=\frontend\models\CdSystems::findOne($rec['source_id'])->name?></td>
            <td>
                <?php
               // $resident = \frontend\models\Resident::findOne(['user_id'=>Yii::$app->user->id]);
              //  if($resident->user_id == Yii::$app->user->id) {
                    echo \yii\helpers\Html::a('', ['/personal-plan/download', 'id' => $rec['id']], ['class' => 'btn btn-primary glyphicon glyphicon-download']);
              //  }
                ?>


            </td>
        </tr>



        <?php
    }
    ?>

    </tbody>
</table>

