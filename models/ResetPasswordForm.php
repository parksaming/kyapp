<?php


namespace app\models;


use Yii;
use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $userCode;

    public function rules()
    {
        return [
            // username required
            ['userCode', 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
        ];
    }


    public function validateUser(){
        $response = true;
        $user = $this->getUser();

        if (!$user) {
            $this->addError('userCode', Yii::t('app','User code is invalid. Try again'));
            $response = false;
        }
        return $response;

    }

    /**
     * Finds user by [[userCode]]
     *
     * @return User|null
     */
    public function getUser()
    {
        $user = User::findByUserCode($this->userCode);
        return $user;
    }
}