<?php
 echo '<div style="text-align: center"><b>Файли роботи</b></div>';
//echo '<br>';
?>

<?php //\yii\widgets\Pjax::begin() ?>
<?php
if(!is_array($nodes)) {
    $nodes_arr = explode(',', $nodes);
}else{
    foreach ($nodes as $node) {
        $nodes_arr[] = $node->id;
    }
}

//var_dump($nodes_arr);
//echo '<h4>'.$job_id.'</h4>'
?>

<table class="table table-striped table-bordered">
<tbody>



<?php

if(empty($job_id)){
    $job_id=$_GET['id'];
}

if(\frontend\models\Resident::findOne(['user_id'=>Yii::$app->user->id])->id
    == \frontend\models\ExecutorAssignment::findOne(['job_id'=>$job_id])->resident_id){
    $show_delete = true;
}else{
    $show_delete = false;
}
?>


<tr>
    <th><?=$searchModel->attributeLabels()['sys_name']?></th>
    <th><?=$searchModel->attributeLabels()['user_name']?></th>
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

     //if(isset($nodes_arr) && !in_array($rec['node_id'], $nodes_arr) || !sizeof($nodes_arr)){
     if(isset($nodes_arr) && !in_array($rec['node_id'], $nodes_arr)){
         $style_back = "background-color: #fee";
     }else{
         $style_back = "background-color: #fff";
     }

     ?>


<tr style="<?= $style_back ?>">
    <td><?=$rec['sys_name']?></td>
    <td><?=$rec['user_name']?></td>
    <td><?=$node->name.' ('.$node->N.')'?></td>
    <td><?=\frontend\models\CdSystems::findOne($rec['source_id'])->name?></td>
    <td style="width: 110px">
        <?=
        \yii\helpers\Html::a('', ['personal-plan/download', 'id' => $rec['id']],['class' => 'btn btn-primary glyphicon glyphicon-download']);
        ?>

        <?php
        if($show_delete)
        echo \yii\helpers\Html::button('', [ 'class' => 'btn btn-default glyphicon glyphicon-remove', 'onclick' =>
        '
        $.get( "'.\yii\helpers\Url::toRoute('/personal-plan/delete-file').'", { id: '.$rec['id'] .', job_id: '.$rec['job_id'].'
        ,
        nodes: $("#personalplan-cur_nodes option").map(function() { return this.value;}).get().join(",")
        } )
        .done(function( data ) {
                                $( "#files-table" ).html( data );
                            }
                        );
        '])
        ?>
    </td>
</tr>



<?php
 }
?>

</tbody>
</table>

