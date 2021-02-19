<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $flow
 * @property string $workDate
 * @property int $userId
 * @property string $workId
 * @property string $eventId
 * @property int $powerAIResult
 * @property string $powerAIInTime
 * @property string $powerAIOutTime
 * @property double $powerAICertainty
 * @property int $workTypeId
 * @property int $status
 * @property string $time
 * @property string $datetime
 * @property string $ObjectRecognitionResult
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flow', 'workDate', 'userId', 'workId', 'eventId', 'time'], 'required'],
            [['workDate', 'datetime'], 'safe'],
            [['userId', 'workId', 'powerAIResult', 'workTypeId', 'status'], 'integer'],
            [['powerAICertainty'], 'number'],
            [['flow', 'ObjectRecognitionResult'], 'string', 'max' => 255],
            [['eventId'], 'string', 'max' => 4],
            [['powerAIInTime', 'powerAIOutTime', 'time'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'flow' => 'Flow',
            'workDate' => 'Work Date',
            'userId' => 'User ID',
            'workId' => 'Work ID',
            'eventId' => 'Event ID',
            'powerAIResult' => 'Power Ai Result',
            'powerAIInTime' => 'Power Ai In Time',
            'powerAIOutTime' => 'Power Ai Out Time',
            'powerAICertainty' => 'Power Ai Certainty',
            'workTypeId' => 'Work Type ID',
            'status' => 'Status',
            'time' => 'Time',
            'datetime' => 'Datetime',
            'ObjectRecognitionResult' => 'Object Recognition Result',
        ];
    }
}
