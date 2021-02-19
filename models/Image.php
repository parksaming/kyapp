<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $checkType
 * @property string $taskId
 * @property int $checkItemId
 * @property string $fileCode
 * @property string $fileName
 * @property int $objectRecognizeResult
 * @property int $isConfirmed
 * @property string $createdAt
 * @property string $updatedAt
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['checkType', 'taskId', 'fileCode', 'fileName'], 'required'],
            [['checkType', 'taskId', 'checkItemId', 'objectRecognizeResult', 'isConfirmed'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['fileCode', 'fileName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'checkType' => 'Check Type',
            'taskId' => 'Task ID',
            'checkItemId' => 'Check Item ID',
            'fileCode' => 'File Code',
            'fileName' => 'File Name',
            'objectRecognizeResult' => 'Object Recognize Result',
            'isConfirmed' => 'Is Confirmed',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
