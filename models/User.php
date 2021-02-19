<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class User
 * @package app\models
 * @property integer $id
 * @property string $userCode
 * @property string $password
 * @property string $email
 * @property boolean $isLocked
 * @property integer $organizationId
 * @property string $workChargeId
 * @property integer $roleId
 * @property string $phone
 * @property string $status
 * @property string $token
 * @property string $tokenCreateAt
 * @property integer $attemptCount
 * @property integer $isDeleted
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $userName
 * @property integer $kyotenType
 * @property string $companyCode
 * @property string $imei
 * @property string $supportStartAt
 * @property string $supportEndAt
 */

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;
    public $accessToken;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['userCode', 'password'], 'required'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUserCode($userCode)
    {
        return static::findOne(['userCode' => $userCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function isFacilityCompany($id){
        $organizationLevel = Organization::findOne($id)->organizationLevelId;
        if($organizationLevel == 5 || $organizationLevel == 4){
            return true;
        }
        return false;
    }

    /**
     * @param $id
     */
    public static function canConfirmWork($id){
        $canConfirm = false;
        $user = User::findOne($id);
        $roleId = $user->roleId;
        $level = Organization::findOne($user->organizationId)->organizationLevelId;
        switch ($roleId){
            case 0:
                break;
            case 2:
                if($level == 4){
                    $canConfirm = true;
                }
                break;
            case 4:
                $permissionByRole4 = [1,2,3];
                if(in_array($level,$permissionByRole4)){
                    $canConfirm = true;
                }
                break;
        }
        return $canConfirm;
    }

    /**
     * @param $roleId
     * @param $organizationId
     * @return bool
     */
    public static function canManageRecommend($roleId, $organizationId){
        $level = Organization::findOne($organizationId)->organizationLevelId;
        if($roleId == 0){
            return true;
        }
        if($roleId == 2 && $level == 4){
            return true;
        }
        return false;

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
