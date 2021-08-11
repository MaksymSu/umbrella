<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cd_systems".
 *
 * @property int $id
 * @property string $name
 * @property string $ext
 * @property string $link
 */
class CdSystems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cd_systems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ext', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'ext' => 'Ext',
            'link' => 'Link',
        ];
    }
}
