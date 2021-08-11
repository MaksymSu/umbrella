<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "job_node".
 *
 * @property int $id
 * @property int $job_id
 * @property int $node_id
 * @property string $modified_at
 *
 * @property PersonalPlan $job
 * @property Tree $node
 */
class JobNode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_node';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'node_id'], 'integer'],
            [['modified_at'], 'safe'],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalPlan::className(), 'targetAttribute' => ['job_id' => 'id']],
            [['node_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::className(), 'targetAttribute' => ['node_id' => 'id']],
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
            'node_id' => 'Node ID',
            'modified_at' => 'Modified At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(PersonalPlan::className(), ['id' => 'job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Tree::className(), ['id' => 'node_id']);
    }
}
