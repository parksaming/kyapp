<?php


namespace app\modules\api\controllers;


use app\Constant\Constant;
use app\models\Organization;
use app\models\User;
use Yii;

class AuthController extends \yii\rest\Controller
{
    public function actionIndex(){
        $userCode = Yii::$app->request->post('code','');
        $password = Yii::$app->request->post('password','');
      //  Yii::error($userCode);
        $user = User::find()->where(['userCode' => $userCode])->one();
        /** @var User $user */
      //  Yii::error($user);
        if($user){
            if($user->isLocked != Constant::IS_LOCKED){
                /** @var Organization $organization */
                $organization = Organization::findOne($user->organizationId);
                if($organization){
                    if($organization->organizationLevelId == Constant::FIFTH_LEVEL_ID && $user->validatePassword($password)){
                        Yii::$app->response->statusCode = 200;
                        //reset isLocked and attemptCount
                        $attributes = [
                            'isLocked' => Constant::UN_LOCKED,
                            'attemptCount' => Constant::MIN_ATTEMPT_COUNT
                        ];
                        $user->updateAttributes($attributes);
                        $listOrganizationId = [
                            [
                                'id' => $user->organizationId,
                                'organizationLevelId' => $organization->organizationLevelId
                            ]
                        ];

                        for($i=0;$i<4;$i++){
                            $organization = Organization::findOne($organization->parentId);
                            $listOrganizationId[] = [
                                'id' => $organization->id ,
                                'organizationLevelId' => $organization->organizationLevelId
                            ];
                        }
                        $response = [
                            'id' => $user->id,
                            'kyotenType' => $user->kyotenType,
                            'listOrganizationId' => $listOrganizationId,
                        ];
                    }else{
                        Yii::$app->response->statusCode = 401;
                        $user->attemptCount = $user->attemptCount+1;
                        if($user->attemptCount == Constant::MAX_ATTEMPT_COUNT){
                            $user->isLocked = Constant::IS_LOCKED;
                        }
                        $user->update();
                        $response = [
                            'errors' => [
                                'code' => 'Usercode or password is incorrect',
                            ],
                        ];
                    }
                }else{
                    Yii::$app->response->statusCode = 500;
                    $response = [
                        'error' => 'An error occurred',
                    ];
                }
            }else{
                Yii::$app->response->statusCode = 401;
                $response = [
                    'errors' => [
                        'message' => 'Your account is blocked',
                    ],
                ];
            }

        }else{
            Yii::$app->response->statusCode = 401;
            $response = [
                'errors' => [
                    'code' => "Usercode is invalid"
                ],
            ];
        }
        return $response;
    }
}