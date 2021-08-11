<?php

namespace frontend\models;

use Yii;
use yii\base\Model;


/**
 * This is the model class for table "themes".
 *
 * @property int $id
 * @property string $number
 * @property int $step
 * @property string $content
 * @property int $type
 * @property string $born
 * @property int $status
 * @property string $desc
 * @property string $deadline
 * @property int $master_div_id
 * @property int $no_norms
 *
 * @property PersonalPlan[] $personalPlans
 * @property Div $masterDiv
 * @property ThemesData[] $themesDatas
 */
class MainTree extends Model
{
    public $theme_id;

    public function rules()
    {
        return [
            [['theme_id'], 'integer'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'theme_id' => 'Тема',
        ];
    }

}
