<?php


namespace app\modules\api\controllers;


use app\models\User;
use app\models\Work;
use Yii;

class UserController extends BaseController
{
    /**
     * @param $userCode
     * @return array
     */
    public function actionWorksByUser($userCode){
        /** @var User $user */
        $user = User::findByUserCode($userCode);
        if($user){
            Yii::$app->response->statusCode = 200;
            $worksByUser = Work::find()
                ->select(['id','code','date','officeComment','status','faOperationId'])
                ->where(['userCode' => $userCode,'date' => Date('Y-m-d')])
                ->all();

            $response = [
                'data' => $worksByUser,
            ];
        }else{
            Yii::$app->response->statusCode = 400;
            $response = [
                'errors' => [
                    "userCode" =>  "User code not exist.",
                ],
            ];
        }

        return $response;
    }

    /**
     * @param $userCode
     * @return array
     */
    public function actionCreateWork($userCode){
        if(Yii::$app->request->post()){
            $requestData = Yii::$app->request->post();
            /** @var User $user */
            $user = User::findByUserCode($userCode);
            if($user){
                $requestData['userCode'] = $userCode;
                $requestData['date'] = Date('Y-m-d');
                $requestData['workedOrganizationId'] = $user->organizationId;
              //  $requestData['start'] = Date('Y-m-d H:i:s');
                $work = new Work;
                $work->attributes = $requestData;
                if($work->save()){
                    Yii::$app->response->statusCode = 200;
                    $response = [
                        'id' => $work->id,
                        'code' => $work->code,
                        'date' => $work->date,
                        'officeComment' => $work->officeComment,
                        'status' => $work->status,
                        'faOperationId' => $work->faOperationId,
                    ];
                }else{
                    Yii::$app->response->statusCode = 400;
                    $response = [
                        'errors' => [
                            "code" =>  "Data input invalid",
                        ],
                    ];
                }

            }else{
                Yii::$app->response->statusCode = 400;
                $response = [
                    'errors' => [
                        'userCode' => 'User code not exist.',
                    ],
                ];
            }
        }else{
            Yii::$app->response->statusCode = 400;
            $response = [
                'errors' => [
                    'userCode' => 'User code not exist.',
                    "code" =>  "Data input invalid",
                ],
            ];
        }
        return $response;

    }
}