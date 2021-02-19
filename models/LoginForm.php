<?php

namespace app\models;

use app\Constant\Constant;
use app\modules\api\controllers\AuthController;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $userCode;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['userCode', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app','Usercode or password is incorrect'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
//    public function login()
//    {
//        if ($this->validate()) {
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
//        }
//        return false;
//    }

     public function login(){
         $user = $this->getUser();
         $response = false;
         if($user){
             if($user->isLocked != Constant::IS_LOCKED){
                 if($user->validatePassword($this->password)){
                     Yii::$app->user->login($user);
                     $response = true;
                 }else{
                     $this->addError('password', Yii::t('app','Usercode or password is incorrect'));
                     $user->attemptCount = $user->attemptCount+1;
                     if($user->attemptCount ==  Constant::MAX_ATTEMPT_COUNT){
                         $user->isLocked = Constant::IS_LOCKED;
                     }
                     $user->update();
                 }

             }else{
                 $this->addError('isBlocked', Yii::t('app','Your account is blocked'));
             }
         }else{
             $this->addError('password', Yii::t('app','Usercode or password is incorrect'));
         }

         return $response;
     }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUserCode($this->userCode);
        }

        return $this->_user;
    }
}
