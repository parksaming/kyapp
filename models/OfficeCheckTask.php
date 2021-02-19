<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "office_check_task".
 *
 * @property int $id
 * @property string $userCode
 * @property string $workId
 * @property int $confirmTypeId
 * @property string $comment
 * @property int $state
 * @property string $createdAt
 * @property string $updatedAt
 */
class OfficeCheckTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'office_check_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userCode', 'workId', 'confirmTypeId', 'state'], 'required'],
            [['workId', 'confirmTypeId', 'state'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['userCode'], 'string', 'max' => 45],
            [['comment'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userCode' => 'User Code',
            'workId' => 'Work ID',
            'confirmTypeId' => 'Confirm Type ID',
            'comment' => 'Comment',
            'state' => 'State',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
