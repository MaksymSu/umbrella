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
 */
class Struct extends \yii\db\ActiveRecord
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
}
