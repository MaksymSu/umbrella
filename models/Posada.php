<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "posada".
 *
 * @property int $id
 * @property string $content
 * @property string $desc
 * @property int $status
 */
class Posada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['status'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'desc' => 'Desc',
            'status' => 'Status',
        ];
    }
}
