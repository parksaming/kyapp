<?php


namespace app\modules\api\controllers;


use app\models\Audio;
use app\models\Image;
use app\models\OfficeCheckTask;
use app\models\SelfCheckTask;
use app\models\Work;
use Yii;
use yii\db\Exception;
use app\Constant\Constant;

class WorkController extends BaseController
{
    /**
     * @param $workId
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($workId){
        $transaction = Yii::$app->db->beginTransaction();
        $requestData = Yii::$app->request->getBodyParams();
        $confirmTypeId = Yii::$app->request->getBodyParam('confirmTypeId');
        $workTypeId = Yii::$app->request->getBodyParam('workTypeId');
        if($workId && ($confirmTypeId || $workTypeId)){
            try{
                /** @var Work $work */
                $work = Work::findOne($workId);
                if($work){
                    /** @var OfficeCheckTask $task */
                    $status = Constant::OFFICE_CHECK_TASK_STATUS;
                    $errors = [];
                    if($confirmTypeId){
                        //Office check task
                        $task = new OfficeCheckTask;
                        $task->confirmTypeId = $confirmTypeId;
                        $task->comment = isset($requestData['comment']) ? $requestData['comment']:'';
                    }
                    if($workTypeId){
                        //Self check task
                        $task = new SelfCheckTask;
                        $task->workTypeId = $workTypeId;
                        $task->isAutomatic = isset($requestData['isAutomatic']) ? $requestData['isAutomatic']:'';
                        $work->start = date('Y-m-d H:i:s');
                        $status = 2;
                    }
                    $task->userCode = $work->userCode;
                    $task->workId = $workId;
                    $task->state = 1;

                    if($task->save()){
                        $saveImageSuccess = $saveAudioSuccess = true;
                        $audioFileName = Yii::$app->request->getBodyParam('audioFileName');
                        $images = Yii::$app->request->getBodyParam('images');
                        $checkType = Yii::$app->request->getBodyParam('checkType');
                        if($audioFileName){
                            $audio = new Audio;
                            $audio->checkType= $checkType;
                            $audio->taskId= $task->id;
                            $audio->fileCode= $audioFileName;
                            $audio->fileName= $audioFileName;
                            $saveAudioSuccess = $audio->save();
                            if(!$saveAudioSuccess){
                                $errors = $audio->errors;
                                $transaction->rollBack();
                                //throw Exception audio error
                                throw new Exception('Saving audio is failed');
                            }
                        }
                        if(is_array($images)){
                            foreach($images as $imageData ){
                                $fileName = isset($imageData['fileName']) ? $imageData['fileName'] : '';
                                $image = new Image;
                                $image->checkType = $checkType;
                                $image->taskId = $task->id;
                                $image->fileCode = $fileName;
                                $image->fileName = $fileName;
                                $image->objectRecognizeResult = isset($imageData['isObjectRecognizeSuccess']) ? $imageData['isObjectRecognizeSuccess']:'';
                                $image->checkItemId = isset($imageData['checkItemId']) ? $imageData['checkItemId']:'';
                                $image->isConfirmed = isset($imageData['isConfirmed']) ? $imageData['isConfirmed']:'';;
                                $saveImageSuccess= $image->save();
                                if(!$saveImageSuccess){
                                    $errors = $image->errors;
                                    $transaction->rollBack();
                                    //throw Exception images error
                                    throw new Exception('Saving images is failed');
                                    break;
                                }

                            }
                        }

                        if($saveAudioSuccess && $saveImageSuccess){
                            //update status in work table
                            $work->status = $status;
                            $work->powerAIImage = Yii::$app->request->getBodyParam('powerAIImage');
                            $work->start = strtotime(Date('Y-m-d H:i:s'));
                            if($work->save()){
                                Yii::$app->response->statusCode = 200;
                                $response = [
                                    'status' => true
                                ];
                            }else{
                                $errors = $work->errors;
                                throw new Exception('Saving work is failed');
                            }

                        }
                    }
                    //Validate fail task
                    else{
                        $transaction->rollBack();
                        $errors = $task->errors;
                        //throw Exception task error
                        throw new Exception('Saving task is failed');
                    }

                }else{
                    $errors = ['workId' => 'WorkId is invalid'];
                    throw new Exception('WorkId not found');
                }
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
                Yii::$app->response->statusCode = 400;
                $response = [
                    'errors' => $errors
                ];
            }

        }
        else{
            Yii::$app->response->statusCode = 400;
            //workId not exists.
            $response = [
                'status' => false
            ];
        }

        return $response;
    }

}