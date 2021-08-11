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
class NormNameInput extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $statuses = ['Не активна', 'Активна'];
    public $variants;
    public $k;

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
            [['variants'], 'string'],
            [['status', 'unit_id'], 'integer'],
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
            'status' => 'Стан',
            'unit_id' => 'Одиниця нормування',
            'updated_at' => 'Оновлено',
            'variants' => 'Варіанти новизна-складність н/г (Через пробіл. Стовпчики - новизна (А-Г). Строки - складність(1-6). Дроб - крапка)'
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

    public function drawTable(){
        $table =  '<table>';
        $table.= '<tr>';
        $novelties = Norm::getNovelties();
        $difficulties = Norm::getDifficulties();
        $table.='<th>&nbsp;</th>';
        foreach ($difficulties as $dif){
            $table.= '<th>'.$dif.'</th>';
        }
        $table.= '</tr>';

        foreach ($novelties as $nov){
            $table.= '<tr>';
            $table.='<th>'.$nov.'</th>';
            foreach ($difficulties as $dif){
                $norm = Norm::findOne(['name_id' => $this->id, 'novelty' => $nov, 'difficulty' => $dif]);
                if($norm)
                $table.= '<td style="padding: 8px;border: 1px solid">'.$norm->value.'</td>';
                else $table.= '<td style="padding: 8px;border: 1px solid"></td>';
            }
            $table.= '</tr>';
        }

        $table.= '</table>';
        return $table;
    }
}
