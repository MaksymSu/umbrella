<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "novelty_group".
 *
 * @property int $id
 * @property int $type
 * @property string $content
 * @property int $source_id
 * @property string $updated_at
 * @property int $status
 * @property string $desc
 * @property string $update_id
 *
 * @property Norms[] $norms
 */
class NoveltyGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'novelty_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'source_id', 'status'], 'integer'],
            [['updated_at'], 'safe'],
            [['desc'], 'string'],
            [['content', 'update_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
    public function getNorms()
    {
        return $this->hasMany(Norms::className(), ['novelty_group_id' => 'id']);
    }
}
