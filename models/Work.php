<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "work".
 *
 * @property int $id
 * @property string $userCode
 * @property int $workedOrganizationId
 * @property string $code
 * @property string $officeComment
 * @property int $status
 * @property string $start
 * @property string $end
 * @property int $checkType
 * @property string $date
 * @property string $powerAIImage
 * @property int $confirmStatus
 * @property int $isObjectRecognizeSuccess
 * @property string $faOperationId
 * @property string $faOperationKind
 * @property string $faOperationDetail
 * @property string $faOperationDate
 * @property string $faConstructionTimePeriod
 * @property string $createdAt
 * @property string $updatedAt
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $peopleAreWorking;
    public $peopleAreWaiting;
    public $selfContent;
    public $officeContent;
    public static function tableName()
    {
        return 'work';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userCode', 'workedOrganizationId', 'code','date'], 'required'],
            [['workedOrganizationId', 'status', 'checkType', 'confirmStatus', 'isObjectRecognizeSuccess'], 'integer'],
            [['start', 'end', 'date'], 'safe'],
            [['userCode', 'code'], 'string', 'max' => 45],
            [['officeComment'], 'string', 'max' => 300],
            [['powerAIImage'], 'string', 'max' => 255],
            [['faOperationId', 'faOperationKind', 'faOperationDate', 'faConstructionTimePeriod'], 'string', 'max' => 30],
            [['faOperationDetail'], 'string', 'max' => 100],
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
            'workedOrganizationId' => 'Worked Organization ID',
            'code' => 'Code',
            'officeComment' => 'Office Comment',
            'status' => 'Status',
            'start' => 'Start',
            'end' => 'End',
            'checkType' => 'Check Type',
            'date' => 'Date',
            'powerAIImage' => 'Power AI Image',
            'confirmStatus' => 'Confirm Status',
            'isObjectRecognizeSuccess' => 'Is Object Recognize Success',
            'faOperationId' => 'Fa Operation ID',
            'faOperationKind' => 'Fa Operation Kind',
            'faOperationDetail' => 'Fa Operation Detail',
            'faOperationDate' => 'Fa Operation Date',
            'faConstructionTimePeriod' => 'Fa Construction Time Period',
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
