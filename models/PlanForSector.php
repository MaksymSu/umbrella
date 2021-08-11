<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "resident".
 *
 * @property int $id
 * @property string $fname
 * @property string $sname
 * @property string $lname
 * @property int $tab
 * @property string $dob
 * @property int $age
 * @property string $photo
 * @property int $struct_id
 * @property int $div_id
 * @property int $sector_id
 * @property int $user_id
 * @property string $posada_name
 * @property string $created_at
 * @property string $desc
 *
 * @property Div $div
 * @property Sectors $sector
 * @property Structs $struct
 */
class PlanForSector extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resident';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tab', 'age', 'struct_id', 'div_id', 'sector_id', 'user_id'], 'integer'],
            [['dob', 'created_at'], 'safe'],
            [['desc'], 'string'],
            [['fname', 'sname', 'lname', 'photo', 'posada_name'], 'string', 'max' => 255],
            [['div_id'], 'exist', 'skipOnError' => true, 'targetClass' => Div::className(), 'targetAttribute' => ['div_id' => 'id']],
            [['sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sectors::className(), 'targetAttribute' => ['sector_id' => 'id']],
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
            'fname' => 'Fname',
            'sname' => 'Sname',
            'lname' => 'Lname',
            'tab' => 'Tab',
            'dob' => 'Dob',
            'age' => 'Age',
            'photo' => 'Photo',
            'struct_id' => 'Struct ID',
            'div_id' => 'Div ID',
            'sector_id' => 'Sector ID',
            'user_id' => 'User ID',
            'posada_name' => 'Posada Name',
            'created_at' => 'Created At',
            'desc' => 'Desc',
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
    public function getSector()
    {
        return $this->hasOne(Sectors::className(), ['id' => 'sector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStruct()
    {
        return $this->hasOne(Structs::className(), ['id' => 'struct_id']);
    }
}
