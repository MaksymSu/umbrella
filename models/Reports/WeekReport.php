<?php

namespace frontend\models\Reports;

use frontend\models\Resident;
use Yii;

/**
 * This is the model class for table "conference".
 *
 * @property int $id
 * @property int $active
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $location
 * @property string $color
 * @property int $resident_id
 *
 * @property Resident $resident
 */
class WeekReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conference';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'resident_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name', 'location', 'color'], 'string', 'max' => 255],
            [['resident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resident::className(), 'targetAttribute' => ['resident_id' => 'id']],
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
            'name' => 'Name',
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
    public function getResident()
    {
        return $this->hasOne(Resident::className(), ['id' => 'resident_id']);
    }
}
