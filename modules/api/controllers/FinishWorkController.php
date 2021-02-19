<?php


namespace app\modules\api\controllers;


use app\models\Log;
use app\models\User;
use app\models\Work;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use app\Constant\Constant;

class FinishWorkController extends BaseController
{
    /**
     * @return array
     */
    public function actionFinishTasks(){
        $transaction = Yii::$app->db->beginTransaction();
        $userCode = Yii::$app->request->getBodyParam('userCode');
        $data = Yii::$app->request->getBodyParam('data');
        /** @var User $user */
        $user = User::findByUserCode($userCode);
        if($user && is_array($data)){
            $errors = [];
            try{
                foreach($data as $row){
                    $isLogSave = true;
                    $row = $this->validateData($row);
                    $workId = $row['workId'];
                    /** @var Work $work */
                    $work = Work::findOne($workId);
                    if($work){
                        $logs = is_array($row['logs']) ? $row['logs'] : '';
                        if($logs){
                            foreach ($logs as $log){
                                $log = $this->validateData($log,true);
                                /** @var Log $newLog */
                                $newLog = new Log;
                                $newLog->workId = $workId;
                                $newLog->flow = $log['flow'];
                                $newLog->eventId = $log['eventId'];
                                $newLog->workTypeId = $log['workTypeId'];
                                $newLog->workDate = date('Y-m-d',intval($log['workDate']));
                                $newLog->status = $log['status'] == true ? 1:0;
                                $newLog->time = date('Y-m-d');
                                $newLog->datetime = date('Y-m-d H:i:s');
                                $newLog->userId = $user->id;
                                $isLogSave = $newLog->save();
                                if(!$isLogSave){
                                    $errors = $newLog->errors;
                                    throw new Exception('Saving log failed');
                                    break;
                                }
                            }
                        }else{
                            $errors =['logs' => 'Logs invalid'];
                            throw new Exception('Logs invalid',$errors);
                        }
                        if($isLogSave){
                            $end = isset($row['end']) ? $row['end'] : '';
                            if($end){
                                $work->end = date('Y-m-d H:i:s',intval($end));
                            }
                            $work->status = 6;
                            if($work->save()){
                                Yii::$app->response->statusCode = 200;
                                $response['response'][] = [
                                    'workId' => $workId,
                                    'status' => true
                                ];
                            }else{
                                $errors = $work->errors;
                                throw new Exception('Updating work failed');
                            }
                        }


                    }else{
                        $errors = ['workId' => 'workId invalid'];
                        throw new Exception('WorkId invalid');
                    }
                }
                $transaction->commit();
            }catch(\Exception $e){
                Yii::$app->response->statusCode = 400;
                $transaction->rollBack();
                $response = [
                    'errors' =>  $errors
                ];
            }

        }else{
            Yii::$app->response->statusCode = 400;
            $response = [
                'errors' => 'User code or data invalid'
            ];
        }

        return $response;
    }

    /**
     * @param $data
     * @param bool $validLogs
     * @return mixed
     */
    private function validateData($data, $validLogs = false){
        $standardAttributes = ['flow','workDate','eventId','workId'];
        if(!$validLogs) {
            if (!isset($data['workId'])) {
                $data['workId'] = '';
            }
        } else {
            foreach ($standardAttributes as $attribute) {
                if (!isset($data[$attribute])) {
                    $data[$attribute] = '';
                }
            }
        }

        return $data;
    }
}