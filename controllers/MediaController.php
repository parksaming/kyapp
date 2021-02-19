<?php


namespace app\controllers;

use yii;
use yii\web\Controller;
use ZipArchive;

class MediaController extends PageController
{
    public function actionView($image){
        $filePath = Yii::$app->params['uploadFolder'].DIRECTORY_SEPARATOR.$image;
        if(file_exists($filePath)){
            return Yii::$app->response->sendFile($filePath, $image);
        }


    }

    public function actionAudio($audio){
        $filePath = Yii::$app->params['uploadFolder'].DIRECTORY_SEPARATOR.$audio;
        if(file_exists($filePath)){
            return Yii::$app->response->sendFile($filePath);
        }
        return;
    }

    public function downloadMedia(){
        if(Yii::$app->request->isAjax){

        }
    }

    public function actionDownload(){
        $path = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR. 'pwd.txt';
        $zipPath = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR."download.zip";
        $zip = new \ZipArchive();
        if($zip->open($zipPath,ZipArchive::OVERWRITE) === true){
            $zip->addFile($path,'pwd.txt');
            $zip->close();
            return Yii::$app->response->sendFile($zipPath,'画像_音声.zip');
        }

    }
}