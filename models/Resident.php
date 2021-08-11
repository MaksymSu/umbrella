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
 *
 * @property Div $div
 * @property Sectors $sector
 * @property Structs $struct
 */
class Resident extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $struct_name;
    public $div_name;
    public $sector_name;

    public $posada_desc;
    public $imageFile;
    public $user_name;

    public $file;
    public $image;

    public $path = 'images/residents/';

    public $phones;

    public $work_modes = ['Тематик','Накладник'];

    public $types = ['Внутрішній', 'Зовнішній'];


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
            [['tab', 'age', 'struct_id', 'div_id', 'sector_id', 'user_id','work_mode', 'type'], 'integer'],
            [['dob'], 'safe'],
            [['fname', 'sname', 'lname', 'photo', 'posada_name'], 'string', 'max' => 255],
            ['posada_name', 'required','message' =>'Потрібно вибрати'],
            [['div_id'], 'exist', 'skipOnError' => true, 'targetClass' => Div::className(), 'targetAttribute' => ['div_id' => 'id']],
            [['sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sectors::className(), 'targetAttribute' => ['sector_id' => 'id']],
            [['struct_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structs::className(), 'targetAttribute' => ['struct_id' => 'id']],
            [['desc'], 'string'],
            ['struct_id', 'required', 'message' =>'Потрібно вибрати'],
            ['div_id', 'required', 'message' =>'Потрібно вибрати'],
            ['sector_id', 'required','message' =>'Потрібно вибрати'],
            ['sname', 'required','message' =>'Не може бути пустим'],
            //[['file'], 'file'],
            [['image'], 'safe'],
            [['image'], 'file', 'extensions'=>'jpg, gif, png'],
            [['phones'], 'safe']


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fname' => "Ім'я",
            'sname' => 'Прізвище',
            'lname' => 'По-батькові',
            'tab' => 'Табельний номер',
            'dob' => 'Дата народження',
            'age' => 'Age',
            'photo' => 'Фото файл',
            'struct_id' => 'Структура',
            'div_id' => 'Підрозділ',
            'sector_id' => 'Сектор',

            'struct_name' => 'Структура',
            'div_name' => 'Відділ',
            'sector_name' => 'Сектор',
            'user_id' => 'Акаунт користувача',

            'posada_name' => 'Посада',
            'posada_desc' => 'Офіційна посада',

            'desc' => 'Додаткова інформація',
            'user_name' => 'Користувач',
            'phones' => 'Номери телефонів',
            'image' => '',
            'work_mode' => 'Режим роботи',
            'type' => 'Тип'
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


    public function getPhones(){
        return $this->hasMany(Phone::className(), ['resident_id' => 'id']);
    }
}
