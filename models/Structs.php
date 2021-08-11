<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "structs".
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property int $status
 *
 * @property Div[] $divs
 * @property Sectors[] $sectors
 */
class Structs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'structs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'desc' => 'Примітки',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivs()
    {
        return $this->hasMany(Div::className(), ['struct_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectors()
    {
        return $this->hasMany(Sectors::className(), ['struct_id' => 'id']);
    }
}
