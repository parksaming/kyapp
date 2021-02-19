<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work_type".
 *
 * @property int $id
 * @property string $name
 * @property int $aerialWork
 * @property string $scene
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $displayOrder
 */
class WorkType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'aerialWork', 'displayOrder'], 'integer'],
            [['name'], 'string', 'max' => 8],
            [['scene'], 'string', 'max' => 17],
            [['createdAt', 'updatedAt'], 'string', 'max' => 26],
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
            'aerialWork' => 'Aerial Work',
            'scene' => 'Scene',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'displayOrder' => 'Display Order',
        ];
    }
}
