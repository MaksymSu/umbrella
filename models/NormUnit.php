<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "norm_unit".
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
class NormUnit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'norm_unit';
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
            'content' => 'Одиниця',
            'source_id' => 'Source ID',
            'updated_at' => 'Оновлено',
            'status' => 'Status',
            'desc' => 'Примітка',
            'update_id' => 'Update ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNorms()
    {
        return $this->hasMany(Norms::className(), ['norm_unit_id' => 'id']);
    }
}
