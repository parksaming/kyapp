<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int $id
 * @property int $organizationLevelId
 * @property string $parentId
 * @property string $organizationCode
 * @property string $organizationName
 * @property string $isDeleted
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $numberOfUsers
 * @property int $displayOrder
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'organizationLevelId', 'numberOfUsers', 'displayOrder'], 'integer'],
            [['parentId'], 'string', 'max' => 2],
            [['organizationCode'], 'string', 'max' => 6],
            [['organizationName'], 'string', 'max' => 9],
            [['isDeleted'], 'string', 'max' => 1],
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
            'organizationLevelId' => 'Organization Level ID',
            'parentId' => 'Parent ID',
            'organizationCode' => 'Organization Code',
            'organizationName' => 'Organization Name',
            'isDeleted' => 'Is Deleted',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'numberOfUsers' => 'Number Of Users',
            'displayOrder' => 'Display Order',
        ];
    }
}
