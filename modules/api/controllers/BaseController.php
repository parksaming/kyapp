<?php


namespace app\modules\api\controllers;


use app\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;

class BaseController extends Controller
{
    /**
     * @return array|mixed
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => function($userName,$password){
                /** @var User $user */
                $user = User::find()->where(['userCode' => $userName])->one();
                if ($user && $user->validatePassword($password)){
                    return $user;
                }
                return null;
            }
        ];
        return $behaviors;
    }
}