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
 *
 * @property PersonalPlan[] $personalPlans
 */
class Themes extends \yii\db\ActiveRecord
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
            [['number', 'step', 'type', 'status', 'master_div_id'], 'integer'],
            [['content'], 'string'],
            [['born'], 'safe'],
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
            'step' => 'Step',
            'content' => 'Content',
            'type' => 'Type',
            'born' => 'Born',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonalPlans()
    {
        return $this->hasMany(PersonalPlan::className(), ['theme_id' => 'id']);
    }

    public function getMasterDiv()
    {
        return $this->hasOne(Div::className(), ['id' => 'master_div_id']);
    }
}
