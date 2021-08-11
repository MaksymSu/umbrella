<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "step".
 *
 * @property int $id
 * @property int $norm_id
 * @property int $type
 * @property string $content
 * @property int $source_id
 * @property string $updated_at
 * @property int $status
 * @property string $desc
 * @property string $update_id
 *
 * @property Norms $norm
 */
class Step extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['norm_id', 'type', 'source_id', 'status'], 'integer'],
            [['updated_at'], 'safe'],
            [['desc'], 'string'],
            [['content', 'update_id'], 'string', 'max' => 255],
            [['norm_id'], 'exist', 'skipOnError' => true, 'targetClass' => Norms::className(), 'targetAttribute' => ['norm_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'norm_id' => 'Norm ID',
            'type' => 'Type',
            'content' => 'Content',
            'source_id' => 'Source ID',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'desc' => 'Desc',
            'update_id' => 'Update ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNorm()
    {
        return $this->hasOne(Norms::className(), ['id' => 'norm_id']);
    }
}
