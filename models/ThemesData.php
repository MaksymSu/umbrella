<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "themes_data".
 *
 * @property int $id
 * @property int $theme_id
 * @property string $created_at
 *
 * @property Themes $theme
 */

class ThemesData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'themes_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'theme_id'], 'integer'],
            [['created_at'], 'safe'],
            [['id'], 'unique'],
            [['theme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Themes::className(), 'targetAttribute' => ['theme_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'theme_id' => 'Theme ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id']);
    }
}
