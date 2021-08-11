<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tree".
 *
 * @property int $id
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 * @property string $icon
 * @property int $icon_type
 * @property int $active
 * @property int $selected
 * @property int $disabled
 * @property int $readonly
 * @property int $visible
 * @property int $collapsed
 * @property int $movable_u
 * @property int $movable_d
 * @property int $movable_l
 * @property int $movable_r
 * @property int $removable
 * @property int $removable_all
 * @property int $child_allowed
 */
class Tree  extends \kartik\tree\models\Tree
{
    /**
     * @inheritdoc
     */
public $avatar;

    public static function tableName()
    {
        return 'tree';
    }

    /**
     * Override isDisabled method if you need as shown in the
     * example below. You can override similarly other methods
     * like isActive, isMovable etc.
     */
    public function isDisabled()
    {
        /*
        if (Yii::$app->user->username !== 'lb') {
            return true;
        }
        */
        return parent::isDisabled();
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Назва',
            'N' => 'Конструкційний номер',
            'resident_id' => 'Елемент добавив',
            'avatar' => 'Зображення'
        ];

    }
    public function rules()
    {
        return [
            [['N'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 255],
            [['resident_id'], 'integer'],
            ['N', 'unique', 'message' => 'Елемент з таким номером вже існує.'],
        ];
    }
}
