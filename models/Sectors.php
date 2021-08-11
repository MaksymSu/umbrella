<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "sectors".
 *
 * @property int $id
 * @property string $name
 * @property int $struct_id
 * @property int $div_id
 * @property string $desc
 * @property int $status
 *
 * @property Div $div
 * @property Structs $struct
 */
class Sectors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $struct_name;
    public $div_name;

    public static function tableName()
    {
        return 'sectors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['struct_id', 'div_id', 'status'], 'integer'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['div_id'], 'exist', 'skipOnError' => true, 'targetClass' => Div::className(), 'targetAttribute' => ['div_id' => 'id']],
            [['struct_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structs::className(), 'targetAttribute' => ['struct_id' => 'id']],
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
            'struct_id' => 'Структура',
            'div_id' => 'Підрозділ',
            'desc' => 'Примітки',
            'status' => 'Status',
            'struct_name' => 'Структура',
            'div_name' => 'Відділ',
        ];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiv()
    {
        return $this->hasOne(Div::className(), ['id' => 'div_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStruct()
    {
        return $this->hasOne(Structs::className(), ['id' => 'struct_id']);
    }
}
