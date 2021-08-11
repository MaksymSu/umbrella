<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "calendar_pattern_name".
 *
 * @property int $id
 * @property string $name
 * @property string $info
 * @property string $modified
 *
 * @property CalendarPattern[] $calendarPatterns
 */
class CalendarPatternName extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $time_table;
   // public $from_now = true;

    public static function tableName()
    {
        return 'calendar_pattern_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modified'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['info'], 'string', 'max' => 1024],
            [['year'], 'number'],
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
            'info' => 'Додатково',
            'modified' => 'Змінено',
            'author_id' => 'Автор',
            'time_table' => 'Код для табелю',
            'year' => 'На рік',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarPatterns()
    {
        return $this->hasMany(CalendarPattern::className(), ['name_id' => 'id']);
    }

    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'author_id']);
    }

    public function getWorkingDays(){
        $wd = TimeTable::find()->select(['color'])->where(['>','hours',0]);
        return CalendarPattern::find()->where(['name_id' => $this->id])->andWhere(['in', 'color', $wd])->count();
    }
}
