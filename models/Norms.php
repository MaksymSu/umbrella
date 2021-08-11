<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "norms".
 *
 * @property int $id
 * @property double $value
 * @property string $job_code
 * @property string $job_name
 * @property int $norm_unit_id
 * @property int $novelty_group_id
 * @property int $difficulty_group_id
 * @property string $updated_at
 * @property int $status
 * @property string $update_id
 * @property string $desc
 *
 * @property DifficultyGroup $difficultyGroup
 * @property NoveltyGroup $noveltyGroup
 * @property NormUnit $normUnit
 * @property Step[] $steps
 */
class Norms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'norms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'number'],
            [['norm_unit_id', 'novelty_group_id', 'difficulty_group_id', 'status'], 'integer'],
            [['updated_at'], 'safe'],
            [['desc'], 'string'],
            [['job_code', 'job_name', 'update_id'], 'string', 'max' => 255],
            [['difficulty_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => DifficultyGroup::className(), 'targetAttribute' => ['difficulty_group_id' => 'id']],
            [['novelty_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoveltyGroup::className(), 'targetAttribute' => ['novelty_group_id' => 'id']],
            [['norm_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => NormUnit::className(), 'targetAttribute' => ['norm_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'job_code' => 'Job Code',
            'job_name' => 'Job Name',
            'norm_unit_id' => 'Norm Unit ID',
            'novelty_group_id' => 'Novelty Group ID',
            'difficulty_group_id' => 'Difficulty Group ID',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'update_id' => 'Update ID',
            'desc' => 'Desc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDifficultyGroup()
    {
        return $this->hasOne(DifficultyGroup::className(), ['id' => 'difficulty_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoveltyGroup()
    {
        return $this->hasOne(NoveltyGroup::className(), ['id' => 'novelty_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNormUnit()
    {
        return $this->hasOne(NormUnit::className(), ['id' => 'norm_unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(Step::className(), ['norm_id' => 'id']);
    }
}
