<?php
namespace frontend\models;
date_default_timezone_set('Europe/Kiev');
use Faker\Provider\DateTime;
use frontend\models\Planning\ExecDivAssignment;
use function GuzzleHttp\Psr7\str;
use function PHPSTORM_META\type;
use yii\helpers\ArrayHelper;

class Basic {

    public static function years(){
        $cur = (integer)date('Y');
        $arr =[];
        $arr[$cur] = $cur;
        for($year = ($cur + 1); $year > $cur-10; $year--){
            $arr[$year] = (string)$year;
        }
        return $arr;
    }

    public static $m_arr = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];

    public static $m_arr2 = ['01'=>'Січень', '02'=>'Лютий', '03'=>'Березень', '04'=>'Квітень', '05'=>'Травень',
        '06'=>'Червень', '07'=>'Липень', '08'=>'Серпень', '09'=>'Вересень', '10'=>'Жовтень', '11'=>'Листопад', '12'=>'Грудень'];

    public static function getWorkingDays($m, $year = false){
        if(!$year)$year=date('Y');
        $wd = [167, 160, 168, 167, 112, 160, 184, 160, 176, 183, 191, 174];
        if((int)$year >= 2020 && \Yii::$app->user->can('system2')){
            return self::getNVKworkingHours($m, $year);
        }
        return $wd[$m-1];

    }

    //Рабочие дни по шаблону НВК
    public static function getNVKworkingDays($m, $year = false){
        if(!$year)$year=date('Y');
            $wd = TimeTable::find()->select(['color'])->where(['>','hours',0]);
       // echo $query->createCommand()->getRawSql();
        return CalendarPattern::find()->where(['like','start_date', $year.'-'])->andWhere(['in', 'color', $wd])->count();
    }

    public static function getNVKworkingHours($m, $year = false){
        if(!$year)$year=date('Y');

        $wd = TimeTable::find()->where(['>','hours',0]);//->all();

        $pattern = CalendarPatternName::findOne(['main'=>1, 'year'=>$year]);
       // echo '<h2>'.$wd->count().'</h2>';
        //return 167;
        $hn=0;
        foreach ($wd->all() as $col){
            $hn += $col->hours * CalendarPattern::find()
                    ->where(['like','start_date', $year.'-'.$m])
                    ->andWhere(['color'=>$col->color, 'name_id'=>$pattern->id])
                    ->count();
        }
        return $hn;
    }

    public static function getTotal($model){
        if($model->norm->name->unit) {
            $unit = $model->norm->name->unit->content;
            if ($model->format_id && $model->isInUnits($unit)) {
                $format_selected = \frontend\models\Format::getFormats()[$model->format_id];
                return $model->useFormat($unit, $format_selected)  * $model->value;
            }
            return $model->norm->value * $model->value;
        }
        return null;
    }

    public static function getTotalAll($job_id){
        $norms = \frontend\models\NormJob::findAll(['job_id' => $job_id]);

        $total = 0;
       // if(\Yii::$app->user->can('system')){
       //     var_dump($norms[0]->format_id);exit();
       // }
        foreach ($norms as $norm){
            $unit = $norm->norm->name->unit->content;
            if ($norm->format_id && $norm->isInUnits($unit)) {
                $format_selected = \frontend\models\Format::getFormats()[$norm->format_id];
                $total += ($norm->useFormat($unit, $format_selected))* $norm->value;
            }

            else $total += ($norm->norm->value * $norm->value);
        }
        return $total;
    }


    public static function getNormReport($model){
        $norms = \frontend\models\NormJob::findAll(['job_id'=>$model->id]);
        $str = '';
        $c = 0;
        $format = '';
        $total_all=0;

        foreach ($norms as $norm){
            $total = 0;
            //if(!$norm || !$norm->norm->unit)return null;
           // if(\Yii::$app->user->can('system')) $str .= '<h3>'.$norm->norm->unit.'</h3>';//!lb

            if($norm->format_id && $norm->norm->name->unit){
                $format_selected = \frontend\models\Format::getFormats()[$norm->format_id];
                $format = ','.$norm->norm->name->unit->content.'=>'.$format_selected;
                $total += ($norm->useFormat($norm->norm->name->unit->content, $format_selected))* $norm->value;
                $total_all += $total;
            }else{
                $format='';
                $total += ($norm->norm->value * $norm->value);
                $total_all += $total;
            }
            $str .= $norm->value. ' X <b>' .$norm->norm->name->code.'</b>('.$norm->norm->novelty.'-'.$norm->norm->difficulty.
                $format.
                ') = <b>'. $total.'</b> н/г ('.
                $norm->norm->name->content
                .')<br>';
        }

        $status = '';
        if(round((float)$total_all, 3) != (float)$model->labor){
            $status = '<b style="color: red">Норм вибрано на '.$total_all.' н/г але не застосовано</b><br>';
        }

       // if(\Yii::$app->user->can("system")){
            if($model->finished_at_fact > $model->finished_at){
                $str .= ' <img src="images/overtime.png" />';
                //return $status.$str;
            }

                $k = (strtotime($model->finished_at_fact) - strtotime($model->started_at))/86400;
                if(!$k)$k=1;
                $l = $model->labor/8;


                if($k < $l && $model->status == 2  && $model->finished_at_fact) {
                    $kk = $l-$k;
                    $star = '';
                    $stars = $star;
                    if($kk < 40) {
                        for ($i = 1; $i < $kk; $i++) {
                            $stars .= ' <img src="images/star3.png" />';
                        }
                    }
                    if($model->started_at_fact < $model->started_at){
                        $stars .= ' <img src="images/guru.png" />';
                    }
                    $str .= $stars;
                }


        //}

      //   if(\Yii::$app->user->can("system")){
      //       $str .= ' l>'.$l.' k>'.$k . ' kk>'.($l - $k);
      //   }
            return $status.$str;
    }

    public static function getNormReport2($job_id){
        $model = PersonalPlan::findOne($job_id);

        $norms = \frontend\models\NormJob::findAll(['job_id'=>$model->id]);
        $str = '';
        $c = 0;
        $format = '';
        $total_all=0;

        foreach ($norms as $norm){
            $total = 0;
            //if(!$norm || !$norm->norm->unit)return null;
            // if(\Yii::$app->user->can('system')) $str .= '<h3>'.$norm->norm->unit.'</h3>';//!lb

            if($norm->format_id && $norm->norm->name->unit){
                $format_selected = \frontend\models\Format::getFormats()[$norm->format_id];
                $format = ','.$norm->norm->name->unit->content.'=>'.$format_selected;
                $total += ($norm->useFormat($norm->norm->name->unit->content, $format_selected))* $norm->value;
                $total_all += $total;
            }else{
                $format='';
                $total += ($norm->norm->value * $norm->value);
                $total_all += $total;
            }
            $str .= $norm->value. ' X <b>' .$norm->norm->name->code.'</b>('.$norm->norm->novelty.'-'.$norm->norm->difficulty.
                $format.
                ') = <b>'. $total.'</b> н/г ('.
                $norm->norm->name->content
                .')<br>';
        }

        $status = '';
        if(round((float)$total_all, 3) != (float)$model->labor){
            $status = '<b style="color: red">Норм вибрано на '.$total_all.' н/г але не застосовано</b><br>';
        }

        // if(\Yii::$app->user->can("system")){
        if($model->finished_at_fact > $model->finished_at){
            $str .= ' <img src="images/overtime.png" />';
            //return $status.$str;
        }

        $k = (strtotime($model->finished_at_fact) - strtotime($model->started_at))/86400;
        if(!$k)$k=1;
        $l = $model->labor/8;


        if($k < $l && $model->status == 2  && $model->finished_at_fact) {
            $kk = $l-$k;
            $star = '';
            $stars = $star;
            if($kk < 40) {
                for ($i = 1; $i < $kk; $i++) {
                    $stars .= ' <img src="images/star3.png" />';
                }
            }
            if($model->started_at_fact < $model->started_at){
                $stars .= ' <img src="images/guru.png" />';
            }
            $str .= $stars;
        }


        //}

        //   if(\Yii::$app->user->can("system")){
        //       $str .= ' l>'.$l.' k>'.$k . ' kk>'.($l - $k);
        //   }
        return $status.$str;
    }


    public static function drawColorSet(){
        return '
        <table style="text-align: center">

  <tr>
    <td style="width: 24px; height: 20px; background-color: #bdf;"> </td>
    <td style="padding: 2px;">  Неробочі дні, згідно графіку НВК</td> 
  </tr>

</table>
        ';

    }

    public static function drawCalendarReport($resident_id){
        $alert = '';
        $year_days = (date('L')?366:365);
        $weekend_days = Conference::find()->where(['resident_id'=>$resident_id, 'color' => '#bdf'])->count();
        if($weekend_days < 2){
            //$weekend_days = Conference::find()->where(['resident_id'=> null, 'color' => '#bdf'])->count();
            $alert .= 'Нe встановлено графік роботи';
        }
        $vacation_days = Conference::find()->where(['resident_id'=>$resident_id, 'color' => '#bdf'])->count();
    }

    public static function drawCalendarReport2($M=true, $sector_id = null, $resident_id = null){
        $m_arr = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
        $year = date('Y');
        $days_in_year = (date('L')?366:365);
        $lazy_days=[];
        $days_in_month =[];
        $work_days =[];
        $current_month = (int)(date('m'));
        $style='';

        echo '<table class="table table-striped table-bordered detail-view"><tbody>';

        echo '<tr>';
        echo "<td><b>Місяць</b></td>";
        for($month = 1; $month <= 12; $month++){
            if($M){
                $mm = $m_arr[$month-1];
            }else{
                $mm = $month;
            }


            if($month == $current_month)$style = 'background-color: lightgreen;';
            else $style='';
            echo "<td style='".$style."'><b>".$mm."</b></td>";
            if($month==3)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8544;кв</td>";
            else if($month==6)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8545;кв</td>";
            else if($month==9)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8546;кв</td>";
            else if($month==12)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8547;кв</td>";

        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>Рік</td>";

        echo '</tr>';

        echo '<tr>';
        echo "<td>Робочих днів</td>";
        for($month = 1; $month <= 12; $month++){
            $days_in_month[] = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';

            $lazy_days[] = Conference::find()->where(['like', 'start_date', $m])
                ->andWhere(['color' => '#bdf', 'resident_id' => $resident_id])
                ->count();
            $work_days[] = $days_in_month[$month-1] - $lazy_days[$month-1];

            echo '<td>'.$work_days[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
            ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Неробочі дні</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $lazy_days[$month - 1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($lazy_days[$month-1]+$lazy_days[$month-2]+$lazy_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($lazy_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Календарних днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $days_in_month[$month-1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($days_in_month[$month-1]+$days_in_month[$month-2]+$days_in_month[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($days_in_month)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Робочих годин</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $work_days[$month-1] * 8 . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".(8*array_sum($work_days))."</td>";
        echo '</tr>';

        if (\Yii::$app->user->can('system')) {
            echo '<tr>';
            echo "<td>Ставка</td>";
            for($month = 1; $month <= 12; $month++) {
                echo '<td>' . round($lazy_days[$month-1]/$work_days[$month-1], 2)  . '</td>';
                if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                    ''
                    ."</td>";
            }
            echo "<td style='background-color: #ffd3bb; font-weight: 600''>".""."</td>";
            echo '</tr>';
        }

        echo '</tbody></table>';


    }

    protected static function drawKvartal($month, $sum){

        if(in_array($month, [3,6,9,12]))
        echo "
        <td>$sum</td>
        ";
    }

    public static function drawCalendarReportResident($M = true, $resident_id = null){
        $m_arr = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
        $year = date('Y');
        $days_in_year = (date('L')?366:365);
        $lazy_days=[];
        $days_in_month =[];
        $work_days =[];
        $work_days_nvk = [];
        $lazy_days_nvk =[];
        $current_month = (int)(date('m'));
        $style='';

        echo '<table class="table table-striped table-bordered detail-view"><tbody>';

        echo '<tr>';
        echo "<td><b>Місяць</b></td>";
        for($month = 1; $month <= 12; $month++){
            if($M){
                $mm = $m_arr[$month-1];
            }else{
                $mm = $month;
            }


            if($month == $current_month)$style = 'background-color: lightgreen;';
            else $style='';
            echo "<td style='".$style."'><b>".$mm."</b></td>";
            if($month==3)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8544;кв</td>";
            else if($month==6)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8545;кв</td>";
            else if($month==9)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8546;кв</td>";
            else if($month==12)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8547;кв</td>";

        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>Рік</td>";

        echo '</tr>';


        echo '<tr>';
        echo "<td>Робочих днів НВК</td>";
        for($month = 1; $month <= 12; $month++){
            $days_in_month[] = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';

            $lazy_days_nvk[] =CalendarPattern::find()->select(['start_date'])->distinct()->where(['like', 'start_date', $m])->andFilterWhere(['name_id' =>1])
                ->count();

            $work_days_nvk[] = $days_in_month[$month-1] - $lazy_days_nvk[$month-1];

            echo '<td>'.$work_days_nvk[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days_nvk[$month-1]+$work_days_nvk[$month-2]+$work_days_nvk[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days_nvk)."</td>";
        echo '</tr>';



        echo '<tr>';
        echo "<td>Робочих днів</td>";
        for($month = 1; $month <= 12; $month++){

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';

            $lazy_days[] = Conference::find()->select(['start_date'])->distinct()->where(['like', 'start_date', $m])
                ->andWhere(['resident_id' => $resident_id])
                ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
                ->count();
            $work_days[] = $days_in_month[$month-1] - $lazy_days[$month-1];

            echo '<td>'.$work_days[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Неробочі дні</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $lazy_days[$month - 1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($lazy_days[$month-1]+$lazy_days[$month-2]+$lazy_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($lazy_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Календарних днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $days_in_month[$month-1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($days_in_month[$month-1]+$days_in_month[$month-2]+$days_in_month[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($days_in_month)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Робочих годин</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $work_days[$month-1] * 8 . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".(8*array_sum($work_days))."</td>";
        echo '</tr>';

        if (\Yii::$app->user->can('system')) {
            echo '<tr>';
            echo "<td>Ставка</td>";
            for($month = 1; $month <= 12; $month++) {
                echo '<td>' . round($work_days[$month-1]/$work_days_nvk[$month-1], 2)  . '</td>';
                if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                    ''
                    ."</td>";
            }
            echo "<td style='background-color: #ffd3bb; font-weight: 600''>".""."</td>";
            echo '</tr>';
        }

        echo '</tbody></table>';

    }

    public static function drawCalendarReportSector($M = true, $sector_id = null){
        $m_arr = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
        $year = date('Y');
        $days_in_year = (date('L')?366:365);
        $lazy_days=[];
        $days_in_month =[];
        $work_days =[];
        $work_days_nvk = [];
        $lazy_days_nvk =[];
        $work_residents = [];
        $current_month = (int)(date('m'));
        $style='';

        echo '<table class="table table-striped table-bordered detail-view"><tbody>';

        echo '<tr>';
        echo "<td><b>Місяць</b></td>";
        for($month = 1; $month <= 12; $month++){
            if($M){
                $mm = $m_arr[$month-1];
            }else{
                $mm = $month;
            }


            if($month == $current_month)$style = 'background-color: lightgreen;';
            else $style='';
            echo "<td style='".$style."'><b>".$mm."</b></td>";
            if($month==3)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8544;кв</td>";
            else if($month==6)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8545;кв</td>";
            else if($month==9)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8546;кв</td>";
            else if($month==12)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8547;кв</td>";

        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>Рік</td>";

        echo '</tr>';


        echo '<tr>';
        echo "<td>Робочих днів НВК</td>";
        for($month = 1; $month <= 12; $month++){
            $days_in_month[] = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';

            $lazy_days_nvk[] =CalendarPattern::find()->select(['start_date'])->distinct()->where(['like', 'start_date', $m])->andFilterWhere(['name_id' =>1])
                ->count();

            $work_days_nvk[] = $days_in_month[$month-1] - $lazy_days_nvk[$month-1];

            echo '<td>'.$work_days_nvk[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days_nvk[$month-1]+$work_days_nvk[$month-2]+$work_days_nvk[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days_nvk)."</td>";
        echo '</tr>';



        echo '<tr>';
        echo "<td>Робочих л.днів</td>";
        for($month = 1; $month <= 12; $month++){

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';
            $residents = Resident::find()->select(['id'])->where(['sector_id' =>$sector_id])->andWhere(['!=', 'div_id', 22])
                ->andFilterWhere(['work_mode'=>0])
            ;
            $work_residents[] =  $residents->count();

            $lazy_days[] = Conference::find()->select(['start_date', 'resident_id'])->distinct()->where(['like', 'start_date', $m])
                ->andFilterWhere(['in', 'resident_id', $residents])
                ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
                ->count();
            $work_days[] = ($days_in_month[$month-1]*$work_residents[$month-1]) - $lazy_days[$month-1];

            echo '<td>'.$work_days[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Неробочіх л.днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $lazy_days[$month - 1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($lazy_days[$month-1]+$lazy_days[$month-2]+$lazy_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($lazy_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Календарних днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $days_in_month[$month-1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($days_in_month[$month-1]+$days_in_month[$month-2]+$days_in_month[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($days_in_month)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Робочих годин</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $work_days[$month-1] * 8 . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".(8*array_sum($work_days))."</td>";
        echo '</tr>';

        if (\Yii::$app->user->can('system')) {
            echo '<tr>';
            echo "<td>Ставка</td>";
            for($month = 1; $month <= 12; $month++) {
                echo '<td>' . round($work_days[$month-1]/($work_days_nvk[$month-1]*$work_residents[$month-1]), 2)  . '</td>';
                if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                    ''
                    ."</td>";
            }
            echo "<td style='background-color: #ffd3bb; font-weight: 600''>".""."</td>";
            echo '</tr>';
        }

        echo '</tbody></table>';

    }


    public static function drawCalendarReportDiv($M = true, $sector_id = null){
        $m_arr = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
        $year = date('Y');
        $days_in_year = (date('L')?366:365);
        $lazy_days=[];
        $days_in_month =[];
        $work_days =[];
        $work_days_nvk = [];
        $lazy_days_nvk =[];
        $work_residents = [];
        $current_month = (int)(date('m'));
        $style='';

        echo '<table class="table table-striped table-bordered detail-view"><tbody>';

        echo '<tr>';
        echo "<td><b>Місяць</b></td>";
        for($month = 1; $month <= 12; $month++){
            if($M){
                $mm = $m_arr[$month-1];
            }else{
                $mm = $month;
            }


            if($month == $current_month)$style = 'background-color: lightgreen;';
            else $style='';
            echo "<td style='".$style."'><b>".$mm."</b></td>";
            if($month==3)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8544;кв</td>";
            else if($month==6)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8545;кв</td>";
            else if($month==9)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8546;кв</td>";
            else if($month==12)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8547;кв</td>";

        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>Рік</td>";

        echo '</tr>';


        echo '<tr>';
        echo "<td>Робочих днів НВК</td>";
        for($month = 1; $month <= 12; $month++){
            $days_in_month[] = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';

            $lazy_days_nvk[] =CalendarPattern::find()->select(['start_date'])->distinct()->where(['like', 'start_date', $m])->andFilterWhere(['name_id' =>1])
                ->count();

            $work_days_nvk[] = $days_in_month[$month-1] - $lazy_days_nvk[$month-1];

            echo '<td>'.$work_days_nvk[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days_nvk[$month-1]+$work_days_nvk[$month-2]+$work_days_nvk[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days_nvk)."</td>";
        echo '</tr>';



        echo '<tr>';
        echo "<td>Робочих л.днів</td>";
        for($month = 1; $month <= 12; $month++){

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';
            $residents = Resident::find()->select(['id'])->where(['div_id' =>$sector_id])->andWhere(['!=', 'div_id', 22])
                ->andFilterWhere(['work_mode'=>0])
            ;
            $work_residents[] =  $residents->count();

            $lazy_days[] = Conference::find()->select(['start_date', 'resident_id'])->distinct()->where(['like', 'start_date', $m])
                ->andFilterWhere(['in', 'resident_id', $residents])
                ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
                ->count();
            $work_days[] = ($days_in_month[$month-1]*$work_residents[$month-1]) - $lazy_days[$month-1];

            echo '<td>'.$work_days[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Неробочіх л.днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $lazy_days[$month - 1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($lazy_days[$month-1]+$lazy_days[$month-2]+$lazy_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($lazy_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Календарних днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $days_in_month[$month-1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($days_in_month[$month-1]+$days_in_month[$month-2]+$days_in_month[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($days_in_month)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Робочих годин</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $work_days[$month-1] * 8 . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".(8*array_sum($work_days))."</td>";
        echo '</tr>';

        if (\Yii::$app->user->can('system')) {
            echo '<tr>';
            echo "<td>Ставка</td>";
            for($month = 1; $month <= 12; $month++) {
                echo '<td>' . round($work_days[$month-1]/($work_days_nvk[$month-1]*$work_residents[$month-1]), 2)  . '</td>';
                if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                    ''
                    ."</td>";
            }
            echo "<td style='background-color: #ffd3bb; font-weight: 600''>".""."</td>";
            echo '</tr>';
        }

        echo '</tbody></table>';

    }

    public static function drawCalendarReportStruct($M = true, $sector_id = null){
        //$n = Resident::find()->where(['struct_id'=>$sector_id]);

        $m_arr = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
        $year = date('Y');
        $days_in_year = (date('L')?366:365);
        $lazy_days=[];
        $days_in_month =[];
        $work_days =[];
        $work_days_nvk = [];
        $lazy_days_nvk =[];
        $work_residents = [];
        $current_month = (int)(date('m'));
        $style='';

        echo '<table class="table table-striped table-bordered detail-view"><tbody>';

        echo '<tr>';
        echo "<td><b>Місяць</b></td>";
        for($month = 1; $month <= 12; $month++){
            if($M){
                $mm = $m_arr[$month-1];
            }else{
                $mm = $month;
            }


            if($month == $current_month)$style = 'background-color: lightgreen;';
            else $style='';
            echo "<td style='".$style."'><b>".$mm."</b></td>";
            if($month==3)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8544;кв</td>";
            else if($month==6)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8545;кв</td>";
            else if($month==9)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8546;кв</td>";
            else if($month==12)echo "<td style='background-color: lightyellow; font-weight: 600''>&#8547;кв</td>";

        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>Рік</td>";

        echo '</tr>';


        echo '<tr>';
        echo "<td>Робочих днів НВК</td>";
        for($month = 1; $month <= 12; $month++){
            $days_in_month[] = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';

            $lazy_days_nvk[] =CalendarPattern::find()->select(['start_date'])->distinct()->where(['like', 'start_date', $m])->andFilterWhere(['name_id' =>1])
                ->count();

            $work_days_nvk[] = $days_in_month[$month-1] - $lazy_days_nvk[$month-1];

            echo '<td>'.$work_days_nvk[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days_nvk[$month-1]+$work_days_nvk[$month-2]+$work_days_nvk[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days_nvk)."</td>";
        echo '</tr>';



        echo '<tr>';
        echo "<td>Робочих л.днів</td>";
        for($month = 1; $month <= 12; $month++){

            $m = '-'.str_pad($month, 2, "0", STR_PAD_LEFT).'-';
            $residents = Resident::find()->select(['id'])->where(['struct_id' =>$sector_id])->andWhere(['!=', 'div_id', 22])
                ->andFilterWhere(['work_mode'=>0])
            ;
            $work_residents[] =  $residents->count();

            $lazy_days[] = Conference::find()->select(['start_date', 'resident_id'])->distinct()->where(['like', 'start_date', $m])
                ->andWhere(['color' => '#bdf'])->andWhere(['in', 'resident_id', $residents])
                ->count();
            $work_days[] = ($days_in_month[$month-1]*$work_residents[$month-1]) - $lazy_days[$month-1];

            echo '<td>'.$work_days[$month-1].'</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($work_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Неробочіх л.днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $lazy_days[$month - 1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($lazy_days[$month-1]+$lazy_days[$month-2]+$lazy_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($lazy_days)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Календарних днів</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $days_in_month[$month-1] . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600''>".
                ($days_in_month[$month-1]+$days_in_month[$month-2]+$days_in_month[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".array_sum($days_in_month)."</td>";
        echo '</tr>';

        echo '<tr>';
        echo "<td>Робочих годин</td>";
        for($month = 1; $month <= 12; $month++) {
            echo '<td>' . $work_days[$month-1] * 8 . '</td>';
            if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                ($work_days[$month-1]+$work_days[$month-2]+$work_days[$month-3])
                ."</td>";
        }
        echo "<td style='background-color: #ffd3bb; font-weight: 600''>".(8*array_sum($work_days))."</td>";
        echo '</tr>';

        if (\Yii::$app->user->can('system')) {
            echo '<tr>';
            echo "<td>Ставка</td>";
            for($month = 1; $month <= 12; $month++) {
                echo '<td>' . round($work_days[$month-1]/($work_days_nvk[$month-1]*$work_residents[$month-1]), 2)  . '</td>';
                if(in_array($month, [3,6,9,12]))echo "<td style='background-color: lightyellow; font-weight: 600'>".
                    ''
                    ."</td>";
            }
            echo "<td style='background-color: #ffd3bb; font-weight: 600''>".""."</td>";
            echo '</tr>';
        }

        echo '</tbody></table>';

    }

    //Суммарная трудоемкость резидента за период, включая не сданое
    public static function getResidentLoad($id, $started_at, $finished_at){
        return PersonalPlan::find()->where(['in', 'id', ExecutorAssignment::find()->select(['job_id'])->where(['resident_id' => $id])])
            ->andFilterWhere(['>=', 'started_at', $started_at])
            ->andFilterWhere(['<=', 'finished_at', $finished_at])->sum('labor');
    }

    //Суммарная трудоемкость резидента за месяц
    public static function getResidentLoadM($id, $m){
        return PersonalPlan::find()->where(['in', 'id', ExecutorAssignment::find()->select(['job_id'])->where(['resident_id' => $id])])
            ->andFilterWhere(['like', 'started_at', date("Y").'-'.$m.'-'])->sum('labor');
    }


    //Суммарная трудоемкость резидента за месяц new
    public static function getResidentLoadMnew($id, $m){
        return PersonalPlan::find()->where(['in', 'id', ExecutorAssignment::find()->select(['job_id'])->where(['resident_id' => $id])])
            ->andFilterWhere(['like', 'started_at', '-'.$m.'-'])->sum('labor');
    }

    public static function getResidentWorkDays($id, $started_at, $finished_at){
        if(date('Y')=='2020'){
            $wd = TimeTable::find()->select(['color'])->where(['>','hours',0]);
            return Conference::find()->where(['resident_id' => $id])->andWhere(['in', 'color', $wd])
                ->andFilterWhere(['>=', 'start_date', $started_at])
                ->andFilterWhere(['<=', 'start_date', $finished_at])
                ->count();
        }


        return Conference::find()->where(['resident_id'=> $id])
            ->andFilterWhere(['>=', 'start_date', $started_at])
            ->andFilterWhere(['<=', 'start_date', $finished_at])
            //->andFilterWhere(['in', 'color', ['#ff0', '#bdf']])
            //->andFilterWhere(['color'=> '#bdf'])
            ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
            ->count();
    }

    public static function getResidentWorkDaysM($id, $m){
        if(date('Y')=='2020'){
            $wd = TimeTable::find()->select(['color'])->where(['>','hours',0]);
            return Conference::find()->where(['resident_id' => $id])->andWhere(['in', 'color', $wd])
                ->andWhere(['like','start_date', '2020-'.$m])->count();
        }


        return cal_days_in_month(CAL_GREGORIAN, $m, date('Y')) - (Conference::find()->where(['resident_id'=> $id])
            ->andFilterWhere(['like', 'start_date', '-'.$m.'-'])
            //->andFilterWhere(['color'=> '#bdf'])
            ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
            ->count());
    }

    public static function getResidentWorkDaysM2($id, $m){
        if(date('Y')=='2020'){
            $wd = TimeTable::find()->select(['color'])->where(['>','hours',0]);
            return Conference::find()->where(['resident_id' => $id])->andWhere(['in', 'color', $wd])
                ->andWhere(['like','start_date', '2020-'.$m]);
        }


        return cal_days_in_month(CAL_GREGORIAN, $m, date('Y')) - (Conference::find()->where(['resident_id'=> $id])
                ->andFilterWhere(['like', 'start_date', '-'.$m.'-'])
                //->andFilterWhere(['color'=> '#bdf'])
                ->andFilterWhere(['in', 'color', ['#bdf', '#ff0', '#faa']])
                );
    }

    //загрузка для карточки работы
    public static function getZagruskaHtml($id, $m, $labor = 0, $percent = 0)
    {
        $zagruzka =  self::getResidentLoadM($id, $m);
        $wd_obj = self::getResidentWorkDaysM2($id, $m);
        $work_days =  $wd_obj->count();//self::getResidentWorkDaysM($id, $m);
        $work_hours = self::getWorkingHours($wd_obj);
        // $zagruzka_cur = $zagruzka + $labor;
        // $zagruzka_cur_perc = ($work_days/100*$zagruzka) + $percent;
        //$per = (($zagruzka+$labor)*100/($work_days*8));
        if(!$work_days)$per=0;
        else $per = (($zagruzka+$labor)*100/$work_hours);//($work_days*8));

        if($per>100)$color = '#d00';
        else $color = '#337ab7';

        return '
               <label for="progress">Загрузка виконавця на '.$m.' місяць </label><span style="float: right">
               <b>MAX '.$work_days.'</b> д. або <b>'.$work_hours.'</b> н.г.</span>
    <div class="progress" id="progress" style="height:30px; background-color: lightgrey">
        <div  class="progress-bar" role="progressbar" style="width: '.
            $per
            .'%;  padding-top: 5px;  background-color: '.$color.'; text-align: left; padding-left: 5px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.
            ((int)$per).'% / '.($zagruzka+$labor).' н.г.'
            .'</div>
    </div>
       ';

    }

    public static function getTotalLabor($provider){
        $sum = 0;
        foreach ($provider as $item){
            if($item['status'] == 2) {
                $sum += $item['labor'];
            }
        }
        return $sum;
    }

    public static function getTotalLabor2($provider){
        $sum = 0;
        foreach ($provider as $item){
           // if($item['status'] == 2) {
                $sum += $item['labor'];
           // }
        }
        return $sum;
    }

    public static function getZagruskaHtml2($id, $m, $labor = 0, $percent = 0)
    {
        $zagruzka =  self::getResidentLoadM($id, $m);
        $wd_obj = self::getResidentWorkDaysM2($id, $m);
        $work_days =  $wd_obj->count();
        $work_hours = self::getWorkingHours($wd_obj);

        if(!$work_days)$per=0;
        else $per = (($zagruzka+$labor)*100/$work_hours);

        if($per>100)$color = '#d00';
        else $color = '#337ab7';

        return '
               <b>MAX '.$work_days.'</b> д. або <b>'.$work_hours.'</b> н.г.</span>
    <div class="progress" id="progress" style="height:30px; background-color: lightgrey">
        <div  class="progress-bar" role="progressbar" style="width: '.
            $per
            .'%;  padding-top: 5px;  background-color: '.$color.'; text-align: left; padding-left: 5px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.
            ((int)$per).'% / '.($zagruzka+$labor).' н.г.'
            .'</div>
    </div>
       ';

    }

    public static function getTotalTerms($provider){
        $max_loaded_sector_id=0;
        $max_n = 0;
        $max_labor = 0;

        //Берем все отфильтрованые работы
        $clone = clone $provider->query;

        $jobs = $clone->select(['id']);

        //Выбираем сектора, которые учавствуют в отфильтрованых работах
        $sectors = ExecDivAssignment::find()->select(['sector_id'])->where(['in', 'job_id', $jobs])->groupBy(['sector_id']);

        //Берем Стартовый день и от него начинаем считать уже розданную трудоемкость за месяц


        //$max_labor = implode(', ', ArrayHelper::map($sectors->all(), 'sector_id', 'sector_id'));


        // Ищем сектор с наименьшим колличестворм людей .. А зачем?
        $sector_max_labor = 0;
        $min = 1000;

        $sector_max_load_id = null;
        $load = 0;

        foreach($sectors->all() as $sector){
            $sector_residents = Resident::find()->where(['sector_id' => $sector->sector_id]);
            $n = $sector_residents->count(); //кол-во народу в секторе
            //Теперь нужно посчитать нагрузку на сектор по графику
            $sector_jobs = ExecDivAssignment::find()->select(['job_id'])->where(['sector_id'=>$sector->sector_id]);
            $sector_labor = PersonalPlan::find()->where(['in', 'id', $sector_jobs])
                ->andFilterWhere((['in', 'id', $jobs]))
                ->sum('labor');
            //$sector_labor  = \frontend\models\Basic::getTotalTerms($provider);

            if($n) {
                $l = $sector_labor / $n;
                if ($l > $load) { //это удельная нагрузка на сектор
                    $load = $l;
                    $sector_max_load_id = $sector->sector_id;
                    $sector_max_labor = $sector_labor;
                }
            }

        }

        //Нашли сектор с наименьшим кол-вом людей
        if ($sec = Sectors::findOne($sector_max_load_id)){
            $s_name = $sec->name;
        }else { $s_name = ' не знайдено';}
        $max_labor =
            '<b>Відібрані роботи потребують <span style="font-size:20px"> '
            .round($load /8, 1)//implode(', ', $arr);
            ."</span> робочих днів структури ЦКБ </b>(по календарному шаблону НВК)<br>"
            .'Найбільш навантажений сектор: <b><font color="#337ab7" size="4">'
            .$s_name
            .'</font></b> <font color="green"> 
<details><summary><b>Варіанти зменьшення часу на роботи</b></summary>
<li>Зкорегувати календарні графіки виконавців найбільш навантаженого сектору (доступно керівнику сектора та економісту. <b>Зараз не використовуеться</b>)</li>
<li>Добавити виконавців у штат найбільш навантаженого сектору (доступно керівнику структури та економісту)</li>
<li>Передати якісь роботи на іньші сектори
</li>
</details>
</font></b>'
            //.' '.$sector_max_load_id

        ;


        return $max_labor;
        //$total_labor = self::getTotalLabor2($provider);

        //$N = Resident::find()->where('div_id')
    }

    //ПЕчать плана-звіта
    public static function drawZvitPrn($provider, $theme_content){
        self::drawHeader($theme_content);
        $divss = self::drawBody($provider);
        $divs = $divss[0];
        $divs_hours = $divss[1];

        self::finalize();

        self::drawDivs($divs,  $provider, $divs_hours);

        //if(\Yii::$app->user->can('system')){
            self::drawDivChiefs($divs);
       // }
    }
    public static function drawHeader($theme_content){


        switch(\Yii::$app->request->get('m')){
            case 'last': $mm = ' за '.date("m.Y", strtotime("-1 month"));break;
            case 'current': $mm =  ' за '.date("m.Y");break;
            case 'all': $mm = ''; break;
            default: $mm = null;
        }


        echo '<div class="row">';
        echo '<div class="col-sm-9"></div>';
        echo '<div class="col-sm-3 hhh">';
        echo 'Затвержую:<br>Заступник генерального директора -<br>начальник ЦКБ "Сокіл"<br> ________________Ю. М. Галушко<br>
            «___» ____________ 20___р.';
        echo '</div>';
        echo '</div>';
        echo '<br><br><br>';

        echo '<div class="text-center"><h4>
             Перелік робіт які виконувалися ЦКБ "Сокіл" по темі '.$theme_content.$mm.'        
            </h4></div>';

        echo '<br>';

        echo '<table border="1"  style="width: 100%;  text-align:center "><tbody>
             <thead style="display: table-header-group">
             
             <tr>
                 <td>№ з/п</td>
                 <td width="200px">Найменування робіт</td>
                 <td width="460px">
                    <table border="1" style="width: 100%; border-bottom: none; border-left: none">
                    <tr>
                    Виконавець
                    </tr>
                    <tr><td width="33%">Відділ</td><td width="33%">Посада</td><td width="">Прізвище, імя, по-батькові</td></tr>
                    </table>
                 </td>
                 <td>
                 <table border="1" style="width: 500px; border-bottom: none; border-left: none">
                    <tr>
                 НОРМИ<br>&nbsp;
                 </tr>
                 <tr><td width="90px">№ норми</td><td width="90px">Новизна</td><td  width="90px">Складність</td><td  width="90px">
                 Одиниця</td><td width="90px">Кількість</td><td width="90px">Н/г</td></tr>
                    </table>
                 </td>
                 
                  
                                
                 <td>
                 <table border="1" style="width: 200px; border-bottom: none; border-left: none;">
                    <tr>
                 Трудомісткість<br>&nbsp;
                 </tr>
                 <tr><td>Н/г</td><td>Л/м</td></tr>
                    </table>
                 </td>
                        
                 <td width="100px">Узгоджено з ВП МОУ 1347</td> 
                 <td>Прим.</td>
             </tr>
             </thead>
             ';

    }
    private function finalize(){
        echo '</tbody></table>';
    }
    public static function drawBody($provider){
        $i =1;

        $divs =[];
        $divs_hours =[];

        //$working_days = self::getWorkingDays($month, $year);

        $provider->query->orderBy([
            'id' => SORT_DESC,
        ]);

        foreach ($provider->query->all() as $job){

            if($job['status'] != 2)continue;

            $j = \frontend\models\ExecutorAssignment::findOne(['job_id'  => $job['id']]);

           // if(!$j->parent_job_id) {
                $report = self::getNormReportShort($job['id']);
           // }
           // else {
           //     $report = self::getNormReportShort(\frontend\models\PersonalPlan::findOne($j->parent_job_id)). ' test';
           // }

            $resident = ExecutorAssignment::findOne(['job_id'=>$job['id']])->resident;
            //if(\Yii::$app->user->can('system')) {
                $ddd = explode('-', $job['started_at']);
                $year = $ddd[0];//(int)date('Y', strtotime($job['started_at']));
                $month = $ddd[1];//(int)date('m');
                $mn =  cal_days_in_month ( CAL_GREGORIAN, $month, $year);
                $started_at = $year.'-'.$month.'-01';
                $finished_at = $year.'-'.$month.'-'.$mn;
                $w_days = self::getResidentWorkingDays($resident->id, $started_at, $finished_at);
                $w_hours = self::getWorkingHours($w_days);
                //echo '<b>'.$w_hours.'</b> - ';
           // }

            //Трудоемкость по отделам
            if(array_key_exists($resident->div->id, $divs)){
                $divs[$resident->div->id] += $job['labor'];
                $divs_hours[$resident->div->id] += $w_hours; //new

            }else{
                $divs += [$resident->div->id => $job['labor']];
                $divs_hours += [$resident->div->id => $w_hours]; //new
            }

            //otladka
           // $vvv=round($job['labor']/(self::getWorkingDays((integer)(explode('-', $job['started_at']))[1])), 2);
            $vvv=round($job['labor']/(self::getWorkingDays($month, $year)), 2);


            if(\Yii::$app->user->can('system')){
                echo $vvv.' - ';
                echo self::getWorkingDays($month, $year);
                echo ' - ';
                $vvv=round($job['labor']/self::getNVKworkingHours($month, $year),2);
                //А нужно часы НВК за месяц!!!
                echo $vvv.'<br>';

            }

            echo '<tr style="text-align: left">
                  <td style="text-align: center">'.$i++.'</td>
                 <td style="padding: 4px"><b>'.$job['content'].'</b><br><small>'.$job['desc'].'</small></td>
                
                  <td><table border="0" style="width: 100%;  border: none">
                  <tr>
                  
                  <td width="33%" style="padding: 4px">'
                .$resident->div->name.
                '</td>
                 
                 <td width="33%"  style="padding: 4px">'
                .AuthItemLb::findOne(['name' => $resident->posada_name])->description.
                '</td>
                 
                 <td width=""  style="padding: 4px">'
                .$resident->sname.' '.$resident->fname.' '.$resident->lname.
                '</td>
                                   
                  </tr></table>
                  </td> 
                
                 <td width="10%">'
                //.NormJob::find()->where(['job_id' => $job['id']])->count().
                .$report.
                '</td>
                                
                <td width="10%"><table>
                <td width="100px" style="text-align: center; padding: 4px">
                '
                .$job['labor'].
                '
                </td>
                <td width="100px" style="text-align: center; padding: 4px">
                '
               // .round($job['labor']/21/8, 2).
                .$vvv.
                //.round($job['labor']/(self::getWorkingDays((integer)(explode('-', $job['started_at']))[1])), 2).
                //.round($job['labor']/$w_hours, 2). //по-новому

                '
                </td>
                </table></td>
                  
                  <td></td>
                  
                  <td></td>
                </tr>';
        }

        return [$divs, $divs_hours];
    }
    public static function getNormReportShort($job_id){
        $norms = \frontend\models\NormJob::findAll(['job_id'=>$job_id]);

        $str = '';
        $c = 0;
        $format = '';
        $total_all=0;

        foreach ($norms as $norm){
            $total = 0;
            //if(!$norm || !$norm->norm->unit)return null;
            // if(\Yii::$app->user->can('system')) $str .= '<h3>'.$norm->norm->unit.'</h3>';//!lb

            if($norm->format_id && $norm->norm->name->unit){
                $format_selected = \frontend\models\Format::getFormats()[$norm->format_id];
                $format = $norm->norm->name->unit->content.'=>'.$format_selected;
                $total += ($norm->useFormat($norm->norm->name->unit->content, $format_selected))* $norm->value;
                $total_all += $total;
            }else{
                $format=$norm->norm->name->unit->content;
                $total += ($norm->norm->value * $norm->value);
                $total_all += $total;
            }

            $str .= '<table><tr>';
            $str .= '<td width="90px"  style="text-align: center;">'.$norm->norm->name->code.'</td>';
            $str .= '<td width="90px" style="text-align: center;">'.$norm->norm->novelty.'</td>';
            $str .= '<td width="90px" style="text-align: center;">'.$norm->norm->difficulty.'</td>';
            $str .= '<td width="90px" style="text-align: center;">'.$format.'</td>';
            $str .= '<td width="90px" style="text-align: center;">'.$norm->value.'</td>';
            $str .= '<td width="60px">'.$total.'</td>';
            $str .= '</tr></table>';
        }

        return $str;
    }
    public static function drawDivs($divs, $provider=null, $divs_hours){
        echo '<br><h4 align="center">У цілому по відділам</h4>';
       echo '<br><table border="1"  style="width: 100%;  text-align:center ">';

       //
       // if(\Yii::$app->user->can('system')) {
        $query = clone $provider->query;
            $fr = $query
               // ->orderBy(['started_at' => SORT_DESC,])
                ->limit('1')->one();
            //if(\Yii::$app->user->can('system')){
               // var_dump($fr->started_at);exit();
            //}
            $mn = 167;
            if($fr) {
                $mn = self::getWorkingDays((integer)(explode('-', $fr->started_at))[1]);
                //   echo '<p>'.(self::getWorkingDays($mn)).'</p>';
                //echo '<p>' . $mn . '</p>';
                //
            }
        //}

       $total = 0;
       $total_div_m = 0;
        foreach ($divs as $key => $div){
            if($key != 19) { //Отдел САПР исключить
                if(\Yii::$app->user->can('system2')){
                    echo '<tr><td width="40%">' . Div::findOne($key)->name . '</td><td width="30%">'
                        . $div . '<b> н/г</b></td><td width="30%">' . $divs_hours[$key] . '<b> л/м</b></td></tr>';
                    $total += $div;
                }else {
                    echo '<tr><td width="40%">' . Div::findOne($key)->name . '</td><td width="30%">'
                        . $div . '<b> н/г</b></td><td width="30%">' . ($div_m = round($div / $mn, 2)) . '<b> л/м</b></td></tr>';
                    $total += $div;
                    $total_div_m += $div_m;
                }
            }
        }

        echo '<tr><td width="40%"><font size="4">Загалом</font></td><td width="30%"><font size="4">'. $total.'</font><b> н/г</b></td><td width="30%"><font size="4">'. $total_div_m/*round($total/$mn,2)*/.'</font><b> л/м</b></td></tr>';

        echo '</table>';
    }
    public static function drawDivChiefs($divs){
        $chiefs_ids = \Yii::$app->authManager->getUserIdsByRole('Керівник відділу');
        $chiefs_ids = array_merge($chiefs_ids, \Yii::$app->authManager->getUserIdsByRole('Керівник відділу +'));
        $chiefs_ids = array_merge($chiefs_ids, \Yii::$app->authManager->getUserIdsByRole('Керівник відділу ++'));

        $chiefs = Resident::find()->where(['in', 'user_id', $chiefs_ids])
            //->andWhere(['in', 'div_id', array_keys($divs)])
            ->andWhere(['!=', 'div_id', 19])//Отдел САПР исключить
            ->all();

            echo '<br><div id="chiefs"><table width="80%">';

        echo '<tr><td><p></p>';
        echo 'Головний конструктор (оптико - електронні прилади)';// - заступник начальника ЦКБ "Сокіл"';
        echo '</td><td><p></p>';
        echo 'Хомченко Олексій Якович</td></tr>';

        echo '<tr><td><p></p>';
        echo 'Головний конструктор (оптико - механічні конструкції)';// - заступник начальника ЦКБ "Сокіл"';
        echo '</td><td><p></p>';
        echo 'Компанієць Юрій Михайлович</td></tr>';

        echo '<tr><td><p></p>';
        echo 'Головний оптик';// - заступник начальника ЦКБ "Сокіл"';
        echo '</td><td><p></p>';
        echo 'Мазурін Ігор Володимирович</td></tr>';

        foreach ($chiefs as $chief){
            if($chief->div->name == 'Звільнені')continue;
            echo '<tr><td><p></p>';
            echo 'Начальник відділу: '.$chief->div->name;
            echo '</td><td><p></p>';
            echo $chief->sname.' '.$chief->fname.' '.$chief->lname.'</td></tr>';
        }

        echo '</table></div>';
    }


   ///Печать плана графика
    public static function drawPlanPrn($provider, $theme_content){

        self::drawHeaderPlan($theme_content);
        $divs = self::drawBodyPlan($provider);
        self::finalize();


        self::drawTotalPlan($provider);

        //if(\Yii::$app->user->can('system')){
        self::drawDivChiefs($divs);
    }
    private static function drawHeaderPlan($theme_content){


        switch(\Yii::$app->request->get('m')){
            case 'last': $mm = ' за '.date("m.Y", strtotime("-1 month"));break;
            case 'current': $mm =  ' за '.date("m.Y");break;
            case 'all': $mm = ''; break;
            default: $mm = null;
        }


        echo '<div class="row">';
        echo '<div class="col-sm-9"></div>';
        echo '<div class="col-sm-3 hhh">';
        echo 'Затвержую:<br>Заступник генерального директора -<br>начальник ЦКБ "Сокіл"<br> ________________Ю. М. Галушко<br>
            «___» ____________ 20___р.';
        echo '</div>';
        echo '</div>';
        echo '<br><br><br>';

        echo '<div class="text-center"><h4>
             Перелік робіт запланованих для виконання ЦКБ "Сокіл" по темі '.$theme_content.$mm.'        
            </h4></div>';

        echo '<br>';

        echo '<table border="1"  style="width: 100%;  text-align:center "><tbody>
             <thead style="display: table-header-group">
             
             <tr>
                 <td width="50px">№ з/п</td>
                 <td width="360px">Найменування робіт</td>

                 <td>
                 <table border="1" style="width: 800px; border-bottom: none; border-left: none">
                    <tr>
                 НОРМИ<br>&nbsp;
                 </tr>
                 <tr><td width="140px">№ норми</td><td width="140px">Новизна</td><td  width="140px">Складність</td><td  width="140px">
                 Одиниця</td><td width="140px">Кількість</td><td width="140px">Н/г</td></tr>
                    </table>
                 </td>
                 
                  
                                
                 <td>
                 <table border="1" style="width: 300px; border-bottom: none; border-left: none;">
                    <tr>
                 Трудомісткість<br>&nbsp;
                 </tr>
                 <tr><td>Н/г</td><td>Л/м</td></tr>
                    </table>
                 </td>
                        
                 <td>Прим.</td>
             </tr>
             </thead>
             ';

    }
    private static function drawBodyPlan($provider){
        $i =1;

        $divs =[];

        foreach ($provider->query->all() as $job){
/*
            if($job['status'] != 2)continue;

            $j = \frontend\models\ExecutorAssignment::findOne(['job_id'  => $job['id']]);
*/
          //  if(!$j->parent_job_id)
                $report = self::getNormReportShortPlan($job['id']);
                /*
                if(\Yii::$app->user->can('system')){
                    $model = PersonalPlan::findOne($job['id']);
                    $report = self::getNormReport(\frontend\models\PersonalPlan::findOne(
                        \frontend\models\ExecutorAssignment::findOne(['job_id'  => $model->id])->parent_job_id)
                    ). 'gaga';
                }
                */
         //   else {
          // //     $report = self::getNormReportShort(\frontend\models\PersonalPlan::findOne($j->parent_job_id));
          //  }
/*
            $resident = ExecutorAssignment::findOne(['job_id'=>$job['id']])->resident;


            //Трудоемкость по отделам
            if(array_key_exists($resident->div->id, $divs)){
                $divs[$resident->div->id] += $job['labor'];
            }else{
                $divs += [$resident->div->id => $job['labor']];
            }
*/
            echo '<tr style="text-align: left">
                  <td style="text-align: center">'.$i++.'</td>
                 <td style="padding: 4px">'.$job['content'].'</td>
         
                
                 <td width="10%">'
                //.NormJob::find()->where(['job_id' => $job['id']])->count().
                .$report.
                '</td>
                                
                <td width="10%"><table>
                <td width="100px" style="text-align: center; padding: 4px">
                '
                .$job['labor'].
                '
                </td>
                <td width="200px" style="text-align: center; padding: 4px">
                '
                // .round($job['labor']/21/8, 2).


                .round($job['labor']/167, 2).
                '
                </td>
                </table></td>
                  
                  <td></td>
                  
                  <td></td>
                </tr>';
        }

        return $divs;
    }
    private static function getNormReportShortPlan($job_id){
        $norms = \frontend\models\NormJob::findAll(['job_id'=>$job_id]);

        $str = '';
        $c = 0;
        $format = '';
        $total_all=0;

        foreach ($norms as $norm){
            $total = 0;
            //if(!$norm || !$norm->norm->unit)return null;
            // if(\Yii::$app->user->can('system')) $str .= '<h3>'.$norm->norm->unit.'</h3>';//!lb

            if($norm->format_id && $norm->norm->name->unit){
                $format_selected = \frontend\models\Format::getFormats()[$norm->format_id];
                $format = $norm->norm->name->unit->content.'=>'.$format_selected;
                $total += ($norm->useFormat($norm->norm->name->unit->content, $format_selected))* $norm->value;
                $total_all += $total;
            }else{
                $format=$norm->norm->name->unit->content;
                $total += ($norm->norm->value * $norm->value);
                $total_all += $total;
            }

            $str .= '<table><tr>';
            $str .= '<td width="140px"  style="text-align: center;">'.$norm->norm->name->code.'</td>';
            $str .= '<td width="140px" style="text-align: center;">'.$norm->norm->novelty.'</td>';
            $str .= '<td width="140px" style="text-align: center;">'.$norm->norm->difficulty.'</td>';
            $str .= '<td width="140px" style="text-align: center;">'.$format.'</td>';
            $str .= '<td width="140px" style="text-align: center;">'.$norm->value.'</td>';
            $str .= '<td width="100px">'.$total.'</td>';
            $str .= '</tr></table>';
        }

        return $str;
    }
    private static function drawTotalPlan($provider){
        $total = self::getTotalLabor2($provider->query->all());
        echo '<table border="1"  style="width: 100%;  text-align:center ">><tr><td width="40%"><font size="4">Загалом</font></td>
        <td width="30%"><font size="4">'. round($total,2).'</font><b> н/г</b></td>
        <td width="30%"><font size="4">'. (round($total/167, 3)).'</font><b> л/м</b></td></tr></table>';

       // echo '<h4 style="text-align: right">Загалом: '.round($total,2)." н/г ".round($total/200,2)."л/м</h4>";
    }

//Загрузка исполнителя для грида начальника сектора
    public static function getZagruskaHtml3($id, $m, $labor = 0, $percent = 0)
    {
        $zagruzka =  self::getResidentLoadM($id, $m);
        $wd_obj = self::getResidentWorkDaysM2($id, $m);
        $work_days =  $wd_obj->count();//self::getResidentWorkDaysM($id, $m);
        $work_hours = self::getWorkingHours($wd_obj);
/*
        if(\Yii::$app->user->can('system')){
            echo '<h2>'.$work_days.' >> '.self::getWorkingHours(self::getResidentWorkDaysM2($id, $m)).'</h2>';
        }
*/
        // $zagruzka_cur = $zagruzka + $labor;
        // $zagruzka_cur_perc = ($work_days/100*$zagruzka) + $percent;
        //$per = (($zagruzka+$labor)*100/($work_days*8));
        if(!$work_days)$per=0;
        else $per = (($zagruzka+$labor)*100/$work_hours);//($work_days*8));

        if($per>100)$color = '#d00';
        else $color = '#337ab7';

        return '

    <div class="progress" id="progress" style="height:10px; background-color: lightgrey">
        <div  class="progress-bar" role="progressbar" style="width: '.
            $per
            .'%;  padding-top: 5px;  background-color: '.$color.'; text-align: left; padding-left: 5px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">'.
            ((int)$per).'% / '.($zagruzka+$labor).' н.г.'
            .'</div>
    </div>
       '
        .((int)$per).'% / '.($zagruzka+$labor).' н.г.'
            ;

    }

//Загальний звіт по темам
    public static function drawZvitPoTemamForDiv($provider, $div_id){
        self::drawHeadZPTFD($provider, $div_id);
        self::drawBodyZPTFD($provider, $div_id);
        self::finalize();
        self::drawFootZPTFD($provider, $div_id);
    }
    private static function drawBodyZPTFD($provider, $div_id){

        $query2 = clone $provider->query;
        $fr = $query2->limit('1')->one();
        //$mn = 166;
        if($fr) $cur_m = explode('-', $fr->started_at)[1];
        else $cur_m = date('m');
        $query = $provider->query->all();//->select(['COUNT(*) AS cnt'])->groupBy(['theme_id']);
        //echo $query->createCommand()->sql;
        //echo '<br>';

        $themes = Theme::find()->all();
        $labor_total = 0;
        $pos = 1;


        foreach ($themes as $theme) {
            //echo $theme->id.' - ';
            $labor = 0;
            foreach ($query as $job) {
                if ($job->theme->id == $theme->id && $job->status ==2){
                    $labor += $job->labor;
                };
            }
            if($labor){
                //echo $theme->number. ' >> '. $labor.'<br>';
                echo '<tr>
                 <td width="50px">'.$pos++.'</td>
                 <td width="500px">'.'<b>'.$theme->number.'</b> '.$theme->content .'</td>
                 <td width="560px">'.$theme->desc.'</td>
                 <td width="300px">'.$labor.' /<b> '.round($labor/self::getWorkingDays((integer)$cur_m),3).'</b></td>

                 <td></td>
             </tr>';
                $labor_total += $labor;
            }
        }

        echo '<tr>
                 <td width="50px">'.'</td>
                 <td width="500px">'.'<b>Загалом</b></td>
                 <td width="560px"></td>
                 <td width="300px">'.$labor_total.' /<b> '.round($labor_total/self::getWorkingDays((integer)$cur_m),3).'</b></td>

                 <td></td>
             </tr>';
        if(\Yii::$app->user->can('system')){
            echo '<h2>'.$cur_m.' >> '.self::getNVKWorkingDays($cur_m).'</h2>';
        }
    }
    private static function drawHeadZPTFD($provider, $div_id){

        $query = clone $provider->query;
        $fr = $query->limit('1')->one();
        $mn = 167;
        if($fr && $fr->started_at){
            $date_arr = explode('-', $fr->started_at);
            $cur_m = $date_arr[1].'.'.$date_arr[0];
        }
        else $cur_m = date('m.Y');

        if($div = Div::findOne($div_id))$div_name = $div->name;
        else $div_name = "не визначено";


        echo '<div class="row">';
        echo '<div class="col-sm-9"></div>';
        echo '<div class="col-sm-3 hhh">';
        echo 'Затвержую:<br>Заступник генерального директора -<br>начальник ЦКБ "Сокіл"<br> ____________Ю. М. Галушко<br>
            «___» ____________ 20___р.';
        echo '</div>';
        echo '</div>';
        echo '<br><br><br>';

        echo '<div class="text-center"><h3>Звіт</h3><h4>
             по номеклатурі по відділу "'.$div_name.'" за '.$cur_m.'    
            </h4></div>';

        echo '<br>';

        echo '<table border="1"  style="width: 100%;  text-align:center "><tbody>
             <thead style="display: table-header-group">
             
             <tr style="font-size: larger">
                 <td width="50px">№ з/п</td>
                 <td width="500px">Тема</td>
                 <td width="560px">Про тему</td>
                 <td width="300px">Трудомісткість н.г. / <b>л.м.</b></td>

                 <td>Прим.</td>
             </tr>
             </thead>
             ';
    }
    private static function drawFootZPTFD($provider, $div_id){
        $chiefs_ids = \Yii::$app->authManager->getUserIdsByRole('Керівник відділу');
        $chiefs_ids = array_merge($chiefs_ids, \Yii::$app->authManager->getUserIdsByRole('Керівник відділу +'));
        $chiefs_ids = array_merge($chiefs_ids, \Yii::$app->authManager->getUserIdsByRole('Керівник відділу ++'));

        $chiefs = Resident::find()->where(['in', 'user_id', $chiefs_ids])
            //->andWhere(['in', 'div_id', array_keys($divs)])
            ->andWhere(['=', 'div_id', $div_id])
            ->all();

        echo '<br><div id="chiefs"><table width="80%">';
        foreach ($chiefs as $chief){
            if($chief->div->name == 'Звільнені')continue;
            echo '<tr><td><p></p>';
            echo 'Начальник відділу: '.$chief->div->name;
            echo '</td><td><p></p>';
            echo $chief->sname.' '.$chief->fname.' '.$chief->lname.'</td></tr>';
        }

        echo '</table></div>';
}

    //Рисуем какие цвета к какому коду по Табелю
    public static function drawTimeTableLegend(){
        $tm = TimeTable::find()->all();
        echo '<details><summary><b>Розподіл кольорів</b> (права кнопка на даті - підказка)</summary><table>';
        foreach ($tm as $item){
            echo
                '<tr>'
                .'<td>'
                .'<div style="width: 25px; height: 25px; margin-bottom:2px; background-color: '.$item->color.'">'
                .'</div>'
                .'</td>'
                .'<td>'
                .'&nbsp;&nbsp;&nbsp;'
                .'<b>'.$item->code.'</b>'
                .'&nbsp;&nbsp;&nbsp;'
                .$item->about
                .'</td>'
                .'</tr>'
            ;
        }
        echo '</table></details>';

    /*
        $days = Basic::getResidentWorkingDays(110, '2018-01-01' , '2020-02-02');
        echo 'Робочих днів у поточному місяці: '.$days->count();
        echo ', годин: ';
        echo $h = Basic::getWorkingHours($days);
*/
    }

    //Получить колличество рабочих дней в текущем году по id шаблона
    public static function getWorkingDaysPattern($id){
        $zeroDays = TimeTable::find()->select('color')->where(['hours' => 0]);

        return CalendarPattern::find()->where(['name_id' => $id])
            ->andWhere(['not in', 'color', $zeroDays])
            ->count();
    }

    //Получить колличество рабочих часов в текущем году по id шаблона
    public static function getWorkingHoursPattern($id){
    }

    //Получить Рабочие дни для резидента на месяц
    public static function getResidentWorkingDays($id, $started_at, $finished_at, $day_left = false, $table = false, $weekend_days_arr = false){
        $working_days_colors = TimeTable::find()->select('color')->where(['>','hours' ,0]);

        if((int)$day_left >1){
            $date_arr = explode('-', $finished_at);
            $finished_at = $date_arr[0].'-'.$date_arr[1].'-'.$day_left;
        }

        $working_days = Conference::find()->where(['resident_id'=> $id])
            ->andFilterWhere(['>=', 'start_date', $started_at])
            ->andFilterWhere(['<=', 'start_date', $finished_at])
            ->andFilterWhere(['in', 'color', $working_days_colors])
        ;

        //test
        if($table){
            $working_days->andFilterWhere(['not in','start_date', $weekend_days_arr]);
        }
        //end test

        return $working_days;
    }

    //Получить выходные и праздники для резидента на месяц
    public static function getResidentWeekendDays($id, $started_at, $finished_at, $day_left = false){

    }

    //Получить рабочие часы из рабочих дней
    public static function getWorkingHours($days){
        if(!$days)return 0;
        $hs = 0;
        foreach ($days->all() as $day) {
            $hs += TimeTable::findOne(['color' => $day->color])->hours;
        }
        return $hs;
    }

    //Рисуем табель
    public static function drawTable($year = false, $month = false, $struct_id = false, $div_id = false, $sector_id = false, $resident_id = false, $days_left = false, $type_selected=0){
        if(!$year)$year = (int)date('Y');
        if(!$month)$month = (int)date('m');

        if($resident_id > 0){
            $residents = Resident::find()->where(['id'=> $resident_id])->all();
        } else
        $residents = Resident::find()->where(['struct_id'=>$struct_id, 'div_id'=>$div_id, 'type' => 0])->orderBy(['sname'=>SORT_ASC])->all();
       // echo $residents->count();
        echo '<table class="table table-striped table-bordered detail-view"><tbody><thead style="display: table-header-group">';
        $i=1;

        //echo $year.' '.$month.'<br>';
        $mn =  cal_days_in_month ( CAL_GREGORIAN, $month, $year);

        $e_days_flag = 0;
        //$date = ;

        echo '<tr>';
        echo '<td>';
        echo '№<br>п/п';
        echo '</td>';

        echo '<td>';
        echo 'П.І.Б.';
        echo '</td>';

        echo '<td>';
        echo 'Таб.№';
        echo '</td>';

        $weekend_days_arr=[];
        for($j = 1; $j<=$mn; $j++){
            if(!$type_selected) {
                echo '<td>';
                echo $j;
                echo '</td>';
//test
                $dd2 = $year.'-'.$month.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);
                $value = CalendarPattern::findOne(['name_id'=>CalendarPatternName::findOne(['main'=>1])->id, 'start_date' => $dd2]);
                $style_h='';
                if($value) {
                    $code = TimeTable::findOne(['color' => $value->color]);
                    if ($code->code == 'Х') {
                        $style_h = 'style="background-color: #ccc !important;"';
                        $weekend_days_arr[]=$value->start_date;
                    }
                }
//end test
            }else{
               // $dd = $year.'-'.$month.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);
                //$value = Conference::findOne(['resident_id' => $resident->id, 'start_date' => $dd]);

                $dd2 = $year.'-'.$month.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);
                $value = CalendarPattern::findOne(['name_id'=>CalendarPatternName::findOne(['main'=>1])->id, 'start_date' => $dd2]);
                $style_h='';
                if($value) {
                    $code = TimeTable::findOne(['color' => $value->color]);
                    if ($code->code == 'Х') {
                        $style_h = 'style="background-color: #ccc !important;"';
                        $weekend_days_arr[]=$value->start_date;
                    }
                }

                echo '<td ' . $style_h . '>';
                echo $j;
                echo '</td>';
                /*
                if(date('N', strtotime($year.'-'.$month.'-'.$j)) >= 6) {
                    echo '<td style="background-color: #ccc !important;">';
                    echo $j;
                    echo '</td>';
                }else{
                    echo '<td>';
                    echo $j;
                    echo '</td>';
                }
                */
            }
        }

        echo '<td>';
        echo 'Дні<br><div id="e-days">роб.</div>';
        echo '</td>';
        echo '<td>';
        echo 'Год.<br>роб.</div>';
        echo '</td>';
        echo '<td>';
        echo 'Ніч-<br>ні';
        echo '</td>';
        echo '<td>';
        echo 'Свят-<br>кові';
        echo '</td>';
        echo '</tr></thead>';

        foreach ($residents as $resident){
            echo '<tr>';
                echo '<td style="min-width: 40px">';
                    echo $i++;
                echo '</td>';

                echo '<td style="min-width: 140px; text-align: left !important">';
                    echo $resident->sname. ' '. mb_substr($resident->fname,0,1).'. '. mb_substr($resident->lname,0,1).'.';
                echo '</td>';

                echo '<td>';
                    echo $resident->tab;
                echo '</td>';


            $weekend_days_n = 0;
            $weekend_hours_n = 0;

            for($j = 1; $j<=$mn; $j++){
                $dd = $year.'-'.$month.'-'.str_pad($j, 2, '0', STR_PAD_LEFT);
                $value = Conference::findOne(['resident_id' => $resident->id, 'start_date' => $dd]);
                $style='';
                    if($value) {
                        $code = TimeTable::findOne(['color' => $value->color]);
                        if (!$type_selected || $code->code != 'Х') {
                            $style = '';

                        } else {
                            $style = 'style="background-color: #ccc !important;"';
                        }
                        if ($type_selected && (explode('-',$value->start_date)[2] <= $days_left+1 || !$days_left)
                            && in_array($value->start_date, $weekend_days_arr) && $code->hours) {
                            $weekend_days_n++;
                            $weekend_hours_n += $code->hours;
                        }
                    }

//Всех закрасить в X

                if($type_selected && !$code->hours){
                       $code->code = 'X';
                }

                echo '<td '.$style.'>';


                $weekend_days_work_n = 0;
                $weekend_hours_work_n = 0;
                $codes_arr = ['8', 'Н1', 'Н2', 'Н3', 'Н4', 'Н5', 'Н6', 'Н7'];

                    if(!$type_selected || in_array($value->start_date, $weekend_days_arr)) {
                        if (empty($days_left)) {
                           // if(in_array($value->start_date, $weekend_days_arr))
                            if(in_array($value->start_date, $weekend_days_arr)){
                                if($type_selected && in_array($value->start_date, $weekend_days_arr)){
                                    echo $code->code;
                                }else {
                                    if(in_array($code->code, $codes_arr)) {
                                        echo 'X';
                                    }else{
                                        echo $code->code;
                                    }
                                }
                            }else{
                                echo $code->code;
                            }

                        } else {
                            if ($j <= $days_left + 1 || !$code->hours) {
                                //echo $code->code;

                                if(in_array($value->start_date, $weekend_days_arr)){


                                    if($type_selected && in_array($value->start_date, $weekend_days_arr)){
                                         echo $code->code;
                                        //$weekend_days_work_n++;
                                    }else {
                                        if(in_array($code->code, $codes_arr)) {
                                            echo 'X';
                                        }else{
                                            echo $code->code;
                                        }
                                        //echo 'X';
                                        //$weekend_days_n--;
                                    }
                                }else{
                                    echo $code->code;
                                }
                                //$weekend_days_work_n++;
                                //  $weekend_hours_work_n+=$weekend_hours_n;
                            }
                        }
                    }
/*
                    if($type_selected){
                        if(in_array($value->start_date, $weekend_days_arr)) {
                            echo $code->code;
                        }
                    }
*/

                echo '</td>';
            }
                    if(!$type_selected) {
                        $w_days = self::getResidentWorkingDays($resident->id, $year . '-' . $month . '-01', $year . '-' . $month . '-' . $mn, $days_left + 1, true, $weekend_days_arr);
                        echo '<td>';
                        echo $w_days->count();//-$weekend_days_work_n;

                            $e_days = self::getResidentEmptyDays($resident->id, $year . '-' . $month . '-01', $year . '-' . $month . '-' . $mn, $days_left + 1);
                            if($e_days->count()) {
                                $e_days_flag = 1;
                                echo '/';
                                echo $e_days->count();
                                if($e_days_flag) {
                                    echo '<script>
                                        document.getElementById("e-days").textContent="р/п";
                                        document.getElementById("e-hours").textContent="/Пр.";
                                    </script>';
                                }
                            }
                        echo '</td>';

                        echo '<td>';
                        echo self::getWorkingHours($w_days);//-$weekend_hours_work_n;
                        /*
                            if($e_days->count()) {
                                echo '/';
                                echo $e_days->count() * 8 ;
                            }
                        */
                        echo '</td>';
                    }else{
                        echo '<td>';

                        echo $weekend_days_n;
                        echo '</td>';

                        echo '<td>';
                        echo $weekend_hours_n;
                        echo '</td>';
                    }
                    echo '<td>';
                    echo '</td>';
                    echo '<td>';
                    echo '</td>';

            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    public static function drawHeadLeftTable($model){
        if($model->struct_id && $model->div_id) {
            echo '
        ДП НВК "ФОТОПРИЛАД"<br>
        ІПН ' . $model->IPN . '
        <br>' . (Struct::findOne($model->struct_id)->name) . ', 
        ' . (Div::findOne($model->div_id)->name) . '
        ';
        }
    }
    public static function drawHeadCenterTable($model){
        if($model->struct_id && $model->div_id) {
            if(is_numeric($model->day_left))$add = ' (проміжний) ';
            else if($model->type_selected)$add = ' (вихідні) ';
            else $add='';
            echo '
        ТАБЕЛЬ '.$add.'<br>
        З ОБЛІКУ ВИКОРИСТАННЯ РОБОЧОГО ЧАСУ<br>
        ЗА '.(self::$m_arr[(int)$model->month-1]).' Місяць '.$model->year.' р.
        ';
        }
    }
    public static function drawFootTable($div_id, $type_selected = false){
        $chiefs_ids = \Yii::$app->authManager->getUserIdsByRole('Керівник відділу');
        $chiefs_ids = array_merge($chiefs_ids, \Yii::$app->authManager->getUserIdsByRole('Керівник відділу +'));
        $chiefs_ids = array_merge($chiefs_ids, \Yii::$app->authManager->getUserIdsByRole('Керівник відділу ++'));

        $chiefs = Resident::find()->where(['in', 'user_id', $chiefs_ids])
            //->andWhere(['in', 'div_id', array_keys($divs)])
            ->andWhere(['=', 'div_id', $div_id])
            ->all();

        echo '<br><div id="chiefs"><table width="80%">';

        echo '<tr><td><p></p>';
        echo 'Відповідальна особа - Технік 1 кат. ';
        echo '</td><td><p></p>';
        echo '<b> Муренець С.М.</b></td></tr>';

        echo '<tr><td><p></p>';
        echo 'Заступник генерального директора начальник ЦКБ "Сокіл"';
        echo '</td><td><p></p>';
        echo '<b>Галушко Ю.М.</b></td></tr>';

        foreach ($chiefs as $chief){
            if($chief->div->name == 'Звільнені')continue;
            echo '<tr><td><p></p>';
            echo 'Начальник відділу: '.$chief->div->name;
            echo '</td><td><p></p>';
            echo '<b>'.$chief->sname.' '.mb_substr($chief->fname,0,1).'. '.mb_substr($chief->lname,0,1).'.</b></td></tr>';
        }

        if(!$type_selected) {
            echo '<tr><td><p></p>';
            echo 'Начальник відділу кадрів';
            echo '</td><td><p></p>';
            echo '<b>Рожко С.П.</b></td></tr>';
        }
            echo '</table></div>';

    }


    // Дней в месяце
    public static function getDaysInMonth($year, $month){
        if(empty($year))$year=date('Y');
        if(empty($month))$month=date('m');

        $mn = cal_days_in_month ( CAL_GREGORIAN, $month, $year);
        $days_arr = [];
        for($d = 1; $d <= $mn; $d++){
            $days_arr[] = $d;
        }
        return $days_arr;
    }


    //Ночные и праздничные


    //Получить рабочие часы в месяце резидента
    public static function getWorkingHoursInMonth($resident_id, $date){
        Conference::findAll()->where(['resident_id' => $resident_id])
            ->andWhere(['start_date', '']);


    }

    //Получить днип простоя резидента
    public static function getResidentEmptyDays($id, $started_at, $finished_at, $day_left = false){
        $empty_days_colors = TimeTable::find()->select('color')->where(['code' => 'П']);

        if((int)$day_left >1){
            $date_arr = explode('-', $finished_at);
            $finished_at = $date_arr[0].'-'.$date_arr[1].'-'.$day_left;
        }

        $empty_days = Conference::find()->where(['resident_id'=> $id])
            ->andFilterWhere(['>=', 'start_date', $started_at])
            ->andFilterWhere(['<=', 'start_date', $finished_at])
            ->andFilterWhere(['in', 'color', $empty_days_colors])
        ;
        return $empty_days;
    }




}