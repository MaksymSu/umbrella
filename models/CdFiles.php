<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cd_files".
 *
 * @property int $id
 * @property int $job_id
 * @property int $node_id
 * @property string $sys_name
 * @property string $user_name
 * @property int $source_id
 * @property string $created_at
 * @property int $resident_id
 */
class CdFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
   // public $resident;
   // public $system;


    public static function tableName()
    {
        return 'cd_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'node_id', 'job_id', 'source_id', 'resident_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['sys_name', 'user_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'node_id' => 'Елемент',
            'job_id' => 'Job ID',
            'sys_name' => 'Внутрiшне iм\'я',
            'user_name' => 'Зовнiшне iм\'я',
            'source_id' => 'П.З.розробки',
            'created_at' => 'Створено',
            'resident_id' => 'Resident ID',
        ];
    }
}
