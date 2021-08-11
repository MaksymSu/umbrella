<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "themes".
 *
 * @property int $id
 * @property int $number
 * @property int $step
 * @property string $content
 * @property int $type
 * @property string $born
 * @property int $status
 */
class Theme extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'themes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'step', 'type', 'status', 'master_div_id', 'no_norms', 'norm_percent_flag'], 'integer'],
            [['content', 'desc'], 'string'],
            [['born', 'deadline'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '№',
            'step' => 'Етап',
            'content' => 'Назва',
            'type' => 'Тип',
            'born' => 'Створено',
            'status' => 'Заборонити корегувати роботи по темі',
            'desc' => 'Детально',
            'deadline' => 'Кінцевий термін виконання',
            'master_div_id' => 'Відповідальний відділ',
            'no_norms' => 'Не застосовувати нормування',
            'norm_percent_flag' => 'Дозволити перевидання по роботам (%)',
        ];
    }

    public function getDiv()
    {
        return $this->hasOne(Div::className(), ['id' => 'master_div_id']);
    }

}
