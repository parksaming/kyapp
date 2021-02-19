<?php


namespace app\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\UploadedFile;

class ObjectController extends BaseController
{
    public function actionUploadFile($fileName){
        $file = UploadedFile::getInstanceByName('object');
        if($file){
            Yii::$app->response->statusCode = 200;
            if(!file_exists(Yii::$app->params['uploadFolder'])){
                mkdir(Yii::$app->params['uploadFolder']);
            }
            $file->saveAs(Yii::$app->params['uploadFolder'].DIRECTORY_SEPARATOR.$fileName);
            $response = [
                'status' => true,
            ];
        }else{
            Yii::$app->response->statusCode = 500;
            $response = [
                'status' => false,
            ];
        }
        return $response;

    }
}