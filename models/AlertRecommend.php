<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alert_recommend".
 *
 * @property int $id
 * @property int $workTypeId
 * @property int $organizationId
 * @property int $flowType
 * @property string $recommend
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $required
 */
class AlertRecommend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alert_recommend';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workTypeId', 'organizationId', 'flowType', 'recommend'], 'required'],
            [['workTypeId', 'organizationId', 'flowType', 'required'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['recommend'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workTypeId' => 'Work Type ID',
            'organizationId' => 'Organization ID',
            'flowType' => 'Flow Type',
            'recommend' => 'Recommend',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'required' => 'Required',
        ];
    }
}
