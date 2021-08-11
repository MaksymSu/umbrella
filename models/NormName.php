<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "norm_name".
 *
 * @property int $id
 * @property string $content
 * @property string $code
 * @property int $status
 * @property string $updated_at
 *
 * @property Norm[] $norms
 */
class NormName extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $novelties = ['А', 'Б','В','Г','Д'];
    public $difficulties = [1,2,3,4,5,6];
    public $novelty_str;
    public $difficulty_str;
    public $job_id;


    public static function tableName()
    {
        return 'norm_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['status'], 'integer'],
            [['updated_at'], 'safe'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Норма',
            'code' => 'Номер',
            'status' => 'Status',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNorms()
    {
        return $this->hasMany(Norm::className(), ['name_id' => 'id']);
    }

    public function getUnit()
    {
        return $this->hasOne(NormUnit::className(), ['id' => 'unit_id']);
    }

    public function isInUnits($str){
        foreach (Format::getFormats() as $unit){
            if (strpos($str, $unit,0)){
                return $unit;
            }
        }
        return false;
    }
}
