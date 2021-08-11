<?php

namespace frontend\models;

use tecnocen\yearcalendar\data\DataItem;
use tecnocen\yearcalendar\data\JsExpressionHelper;
use Yii;

/**
 * This is the model class for table "calendar_pattern".
 *
 * @property int $id
 * @property int $active
 * @property int $name_id
 * @property string $start_date
 * @property string $end_date
 * @property string $location
 * @property string $color
 * @property int $resident_id
 *
 * @property CalendarPatternName $name
 */
class CalendarPattern extends \yii\db\ActiveRecord implements DataItem
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar_pattern';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'name_id', 'resident_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['location', 'color'], 'string', 'max' => 255],
            [['name_id'], 'exist', 'skipOnError' => true, 'targetClass' => CalendarPatternName::className(), 'targetAttribute' => ['name_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active' => 'Active',
            'name_id' => 'Name ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'location' => 'Location',
            'color' => 'Color',
            'resident_id' => 'Resident ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(CalendarPatternName::className(), ['id' => 'name_id']);
    }



    public function getStartDate()
    {
        // var_dump($this->start_date);
        // exit();


        return JsExpressionHelper::parse($this->start_date);
    }

    public function getEndDate()
    {
        return JsExpressionHelper::parse($this->end_date);
    }

    // rest of the active record code.
}
