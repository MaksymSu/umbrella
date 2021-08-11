<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "div".
 *
 * @property int $id
 * @property string $name
 * @property int $struct_id
 * @property string $desc
 * @property int $status
 */
class Div extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $struct_name;

    public static function tableName()
    {
        return 'div';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['struct_id', 'status'], 'integer'],
            [['desc'], 'string'],
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
            'struct_id' => 'Struct ID',
            'desc' => 'Примітки',
            'status' => 'Статус',
            'struct_name' => 'Структура',
        ];
    }

    public function getStruct()
    {
        return $this->hasOne(Structs::className(), ['id' => 'struct_id']);
    }
}
