<?php

namespace frontend\models\Planning;

use frontend\models\Div;
use frontend\models\Sectors;
use Yii;

/**
 * This is the model class for table "exec_div_assignment".
 *
 * @property int $id
 * @property int $job_id
 * @property int $div_id
 * @property int $persent
 * @property string $created_at
 * @property int $sorcerer_id
 * @property int $status
 * @property string $desc
 */
class ExecDivAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exec_div_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'div_id', 'persent', 'sorcerer_id', 'status', 'sector_id', 'div_id', 'master_div_id'], 'integer'],
            [['created_at'], 'safe'],
            [['desc'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job ID',
            'div_id' => 'Div ID',
            'persent' => 'Persent',
            'created_at' => 'Created At',
            'sorcerer_id' => 'Sorcerer ID',
            'status' => 'Status',
            'desc' => 'Desc',
        ];
    }

    public function getDiv()
    {
        return $this->hasOne(Div::className(), ['id' => 'div_id']);
    }

    public function getSector()
    {
        return $this->hasOne(Sectors::className(), ['id' => 'sector_id']);
    }

    public function getMasterDiv()
    {
        return $this->hasOne(Div::className(), ['id' => 'master_div_id']);
    }
}
