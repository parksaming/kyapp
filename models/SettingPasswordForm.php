<?php


namespace app\models;

use Yii;
use yii\base\Model;

class SettingPasswordForm extends Model
{
    public $userCode;
    public $password;
    public $repassword;

    public function rules()
    {
        return [
            [['userCode','password'], 'required'],
            ['repassword','required','whenClient' => "function(attribute,value){
                        return $('#setting-pwd').val() != '';
                    }"
            ],
            [['repassword'], 'compare', 'compareAttribute' => 'password', 'whenClient' => "function(attribute,value){
                        var password = $('#setting-pwd').val();
                        var repassword = $('#setting-re-pwd').val();
                        return password != '' && repassword != '';
                    
                    }"
            ],
            ['password','string','min' => 8,'max' => 16,'when' => function($model){
                           return $model->password != '';
                    },
                'enableClientValidation' => false,
            ],
            ['password','validatePassword']
        ];
    }

    public function validatePassword($attribute,$params){
        $password = str_split($this->$attribute);
        $isNumber = $isLowerCase = $isUpperCase = $isSpecialCharacter = $hasUserCode = false;
        if(stripos($this->$attribute,$this->userCode)){
            $hasUserCode = true;
        }

        if(!$hasUserCode){
            $check = 0;
            foreach ($password as $character){
                if(ctype_digit($character)){
                    $isNumber = true;
                }
                else if(ctype_upper($character)){
                    $isUpperCase = true;
                }
                else if(ctype_lower($character)){
                    $isLowerCase = true;
                }
                else{
                    $isSpecialCharacter = true;
                }
                $check = $isNumber + $isUpperCase + $isLowerCase + $isSpecialCharacter;
                if ($check >= 2){
                    break;
                }

            }
            if($check >= 2){
                Yii::$app->session->setFlash('sucess','Validate Success');
            }else{
                $this->addError('server-error',Yii::t('app','Password include more than 2 types character such as: number and upper case alphabet or lower case alphabet,etc,... '));
            }
        }else{
            $this->addError('server-error',Yii::t('app','Not using usercode for password'));
        }


    }

}