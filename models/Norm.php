<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "norm".
 *
 * @property int $id
 * @property int $name_id
 * @property double $value
 * @property int $unit_id
 * @property string $novelty
 * @property int $difficulty
 * @property int $status
 * @property string $updated_at
 *
 * @property NormName $name
 * @property NormJob[] $normJobs
 */
class Norm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static $novelties = ['А', 'Б','В','Г','Д'];
    public static $difficulties = [1,2,3,4,5,6];
    public $novelty_str;
    public $difficulty_str;
    public $content_str;


    public static function tableName()
    {
        return 'norm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_id', 'unit_id', 'difficulty', 'status'], 'integer'],
            [['novelty_str', 'content_str'], 'string'],
            [['value'], 'number'],
            [['updated_at'], 'safe'],
            [['novelty'], 'string', 'max' => 1],
            [['name_id'], 'exist', 'skipOnError' => true, 'targetClass' => NormName::className(), 'targetAttribute' => ['name_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => NormName::className(), 'targetAttribute' => ['unit_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_id' => 'Робота',
            'value' => 'Норма',
            'unit_id' => 'Одиниця нормування',
            'novelty' => 'Новизна',
            'difficulty' => 'Складність',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'content_str' => 'База норм',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(NormName::className(), ['id' => 'name_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(NormUnit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormJobs()
    {
        return $this->hasMany(NormJob::className(), ['norm_id' => 'id']);
    }


    public static function loadNorms(){

        $file = file_get_contents('UtilsData/n2.txt');

        $arr1 = explode("\r", $file);

        $super = [];


        $dif_arr = ['А', 'Б', 'В', 'Г', 'Д'];

        $units =[];

        foreach ($arr1 as $key=>$arr){

            if(strlen($arr) > 16 && !strpos($arr, '%') && strpos($arr1[$key-1], '-')) {
                $line = ['num' => $arr1[$key - 1],
                    'content' => $arr,
                    'unit' => $arr1[$key + 1],
                    //'novelty' => $arr1[$key + 2],
                ];

                if($arr1[$key + 2] == 'А-Д'){
                    if($arr1[$key + 3] == '')
                        $line['novelty'] = [
                            'А' =>  [$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5]],
                            'Б' =>  [$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5]],
                            'В' =>  [$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5]],
                            'Г' =>  [$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5]],
                            'Д' =>  [$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5],$arr1[$key + 5]],
                        ];
                    else $line['novelty'] = [
                        'А' =>  [$arr1[$key + 3],$arr1[$key + 4],$arr1[$key + 5],$arr1[$key + 6],$arr1[$key + 7],$arr1[$key + 8]],
                        'Б' =>  [$arr1[$key + 3],$arr1[$key + 4],$arr1[$key + 5],$arr1[$key + 6],$arr1[$key + 7],$arr1[$key + 8]],
                        'В' =>  [$arr1[$key + 3],$arr1[$key + 4],$arr1[$key + 5],$arr1[$key + 6],$arr1[$key + 7],$arr1[$key + 8]],
                        'Г' =>  [$arr1[$key + 3],$arr1[$key + 4],$arr1[$key + 5],$arr1[$key + 6],$arr1[$key + 7],$arr1[$key + 8]],
                        'Д' =>  [$arr1[$key + 3],$arr1[$key + 4],$arr1[$key + 5],$arr1[$key + 6],$arr1[$key + 7],$arr1[$key + 8]],
                    ];
                }elseif ($arr1[$key + 2] == 'А'){
                    $line['novelty'] = [
                        'А' =>  [$arr1[$key + 3],$arr1[$key + 4],$arr1[$key + 5],$arr1[$key + 6],$arr1[$key + 7],$arr1[$key + 8]],
                        'Б' =>  [$arr1[$key + 13],$arr1[$key + 14],$arr1[$key + 15],$arr1[$key + 16],$arr1[$key + 17],$arr1[$key + 18]],
                        'В' =>  [$arr1[$key + 23],$arr1[$key + 24],$arr1[$key + 25],$arr1[$key + 26],$arr1[$key + 27],$arr1[$key + 28]],
                        'Г' =>  [$arr1[$key + 33],$arr1[$key + 34],$arr1[$key + 35],$arr1[$key + 36],$arr1[$key + 37],$arr1[$key + 38]],
                        'Д' =>  [$arr1[$key + 43],$arr1[$key + 44],$arr1[$key + 45],$arr1[$key + 46],$arr1[$key + 47],$arr1[$key + 48]],
                    ];
                }

                $super[] = $line;
            }

            //echo $arr.'<hr>';
        }


        foreach ($super as $item){
            echo $item['num'].' > '.
                $item['content'].' > '.
                $item['unit'].'<br>';
            //   var_dump($item['novelty']); echo '<br>';
            /*
                var_dump($item['novelty']['А']); echo '<br>';
                var_dump($item['novelty']['Б']); echo '<br>';
                var_dump($item['novelty']['В']); echo '<br>';
                var_dump($item['novelty']['Г']); echo '<br>';
                var_dump($item['novelty']['Д']); echo '<br>';
            */
            // echo '<hr>';


            $norm_name = new \frontend\models\NormName();
            $norm_name->content = $item['content'];
            $norm_name->code = $item['num'];
            $norm_name->save();

            $cur_unit = NormUnit::findOne(['content' => $item['unit']]);

            if(!$cur_unit) {
                $norm_unit = new \frontend\models\NormUnit();
                $norm_unit->content = $item['unit'];
                $norm_unit->save();
            }else {
                $norm_unit=$cur_unit;
            }


            if(!array_key_exists ('novelty', $item))continue;
            foreach ($item['novelty'] as $key=>$nov) {

                for ($i=1; $i<7; $i++) {
                    $norm = new \frontend\models\Norm();
                    $norm->name_id = $norm_name->id;
                    $norm->unit_id = $norm_unit->id;
                    $norm->novelty = $key;
                    $norm->difficulty = $i;
                    $norm->value = (double)$nov[$i-1];

                    if(!$norm->value) {
                        $norm->value = (double)$nov[2];
                    }

                    $norm->save();
                }
            }

            //break;

        }
    }


    public static function getNovelties(){
        return self::$novelties;
    }

    public static function getDifficulties(){
        return self::$difficulties;
    }
}
