<?php

use frontend\models\Sectors;
use frontend\models\Div;
use frontend\models\Resident;
use frontend\models\Basic;



//echo $m.'<br>';


$divs = Div::find()->where(['not in','id',['24','22','12']])->all();
    foreach ($divs as $div){
        echo '<h3>'.$div->name.'</h3>';
        $sectors = Sectors::find()->where(['div_id'=>$div->id])->all();
        foreach ($sectors as $sector){
            echo '<hr><h4 style="margin-left: 40px">'.$sector->name.'</h4>';
            $residents = Resident::find()->where(['sector_id'=>$sector->id, 'work_mode'=>0])->all();
            foreach ($residents as $resident){
                echo '<div style="margin-left: 80px; max-width: 140px" class="col-sm-3"><b>'.$resident->sname.' </b>'.$resident->fname.' '.$resident->lname.'</div>';
                echo '<div style="margin-left: 120px; max-width: 50%">'.Basic::getZagruskaHtml2($resident->id, $m).'</div>';
            }
        }
        echo '<br>';
    }

