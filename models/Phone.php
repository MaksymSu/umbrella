<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "phone".
 *
 * @property int $id
 * @property string $number
 * @property int $type
 * @property int $resident_id
 *
 * @property Resident $resident
 */
class Phone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'resident_id'], 'integer'],
            [['number'], 'string', 'max' => 255],
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
            'number' => 'Number',
            'type' => 'Type',
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
