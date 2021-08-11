<?php

namespace frontend\models;

use function Symfony\Component\Debug\Tests\testHeader;
use Yii;

/**
 * This is the model class for table "norm_job".
 *
 * @property int $id
 * @property int $job_id
 * @property int $norm_id
 * @property int $value
 * @property string $format_id
 *
 * @property Norm $norm
 * @property PersonalPlan $job
 */
class NormJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $novelties = ['А', 'Б','В','Г','Д'];
    public $difficulties = [1,2,3,4,5,6];
    public $novelty;
    public $difficulty;
    public $units = ['А4','А3','А2','А1','А0'];

    public static function tableName()
    {
        return 'norm_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'norm_id', 'value', 'format_id'], 'integer'],
            //[['format_id'], 'string', 'max' => 2],
            [['norm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Norm::className(), 'targetAttribute' => ['norm_id' => 'id']],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalPlan::className(), 'targetAttribute' => ['job_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'norm_id' => 'Norm ID',
            'value' => 'Кількість',
            'format_id' => 'Одиниця',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNorm()
    {
        return $this->hasOne(Norm::className(), ['id' => 'norm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(PersonalPlan::className(), ['id' => 'job_id']);
    }

    public function isInUnits($str){
        foreach (Format::getFormats() as $unit){
            if (strpos($str, $unit,0)){
                return $unit;
            }
        }
        return null;
    }

    public function useFormat($format_norm, $format_selected){
        if(Yii::$app->user->can('system')){
            file_put_contents('ffffffffffffff.txt', $format_norm.' - '.$format_selected."\n");
        }

        $arr = Format::getArr();
        $k = $arr[$this->isInUnits($format_norm)][$format_selected];
       $res = $this->norm->value  *  $k;
        return $res;
    }

}
