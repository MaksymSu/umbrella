<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "time_table".
 *
 * @property int $id
 * @property string $code
 * @property string $color
 * @property string $about
 * @property string $created_at
 */
class TimeTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['code'], 'string', 'max' => 3],
            [['color', 'about'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['color'], 'unique'],
            [['hours'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'color' => 'Колір',
            'about' => 'Повна назва',
            'created_at' => 'Створено',
            'hours' => 'Годин',
        ];
    }
}
