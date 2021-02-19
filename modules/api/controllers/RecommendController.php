<?php


namespace app\modules\api\controllers;

use Yii;
use app\models\AlertRecommend;
use app\models\MasterUpdate;
use yii\rest\Controller;

class RecommendController extends BaseController
{
    public function actionRecommend($time){
        if(preg_match('/^[0-9]*$/',$time)){
            $latestUpdate = MasterUpdate::findOne(['key' =>'recommend']);
            if($latestUpdate){
                Yii::$app->response->statusCode = 200;
                $latestTime = strtotime($latestUpdate->updateTime);
                if($latestTime > $time){
                    $recommends = AlertRecommend::find()->all();
                    $response = ['data' => $recommends];
                }else{
                    $response = ['data' => []];
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
                'data' => [
                    'errors' => [
                        'time' => 'Time format invalid.'
                    ]
                ]

            ];
        }

        return $response;
    }
}