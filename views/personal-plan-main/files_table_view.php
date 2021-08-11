
<div style="text-align: center"><b>Файли роботи</b></div>


<table class="table table-striped table-bordered">
    <tbody>

    <tr>
        <th>Файл</th>
        <th><?=$searchModel->attributeLabels()['node_id']?></th>
        <th><?=$searchModel->attributeLabels()['source_id']?></th>
        <th></th>
    </tr>

    <?php
    foreach ($dataProvider->query->all() as $rec){
        $node = \frontend\models\Tree::findOne($rec['node_id']);
        if($node){
            $node_name = $node->name;
            $node_N = $node->N;
        }else{
            $node_name = null;
            $node_N = null;
        }

        if(isset($nodes_arr) && !in_array($rec['node_id'], $nodes_arr)){
            $style_back = "background-color: #fee";
        }else{
            $style_back = "background-color: #fff";
        }

        ?>


        <tr style="<?= $style_back ?>">
            <td><?=$rec['sys_name']?></td>
            <td><?=$node->name.' ('.$node->N.')'?></td>
            <td><?=\frontend\models\CdSystems::findOne($rec['source_id'])->name?></td>
            <td>
                <?=
                \yii\helpers\Html::a('', ['personal-plan/download', 'id' => $rec['id']],['class' => 'btn btn-primary glyphicon glyphicon-download']);
                ?>


            </td>
        </tr>



        <?php
    }
    ?>

    </tbody>
</table>

