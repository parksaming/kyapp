<?php


namespace app\controllers;


use app\models\Audio;
use app\models\ConfirmType;
use app\models\FilterForm;
use app\models\Image;
use app\models\Log;
use app\models\OfficeCheckTask;
use app\models\Organization;
use app\models\SelfCheckTask;
use app\models\User;
use app\models\Work;
use app\models\WorkType;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use app\Constant\Constant;
use yii\web\Response;
use ZipArchive;

class WorkController extends PageController
{
   // private $statusWork = ['Confirmed','Comment','Not finish yet','Blank'];

    public function actionWorkDetail($id){
        $workDetail = $this->getWorkDetail($id);
        if($workDetail === false){
            return 'No data invalid';
        }
        return $this->render('work-detail',['work' => $workDetail]);
    }

    private function getWorkDetail($id){
        $work = Work::findOne($id);
        if($work){
            $user = User::findByUserCode($work->userCode);
            $data = [
                'id' => $id,
                'userCode' => $work->userCode,
                'userName' => $user->userName,
                'phone' => $user->phone,
                'start' => $work->start,
                'end' => $work->end,
                'status' => $work->status,
                'workContent' => '',
                'images' => '',
                'audio' => '',
            ];
            $data['checkType'] = $work->checkType;
            $isOfficeTask = $work->checkType == Constant::OFFICE_TASK  ? true : false;
            if($isOfficeTask){
                $task = OfficeCheckTask::findOne(['workId' => $id]);
            }else{
                $task = SelfCheckTask::findOne(['workId' => $id]);
            }
            if($task){
                $audio = Audio::findOne(['taskId' => $task->id,'checkType' => $work->checkType]);
                $images = Image::findAll(['taskId' => $task->id,'checkType' => $work->checkType]);

                if($isOfficeTask){
                    $confirmTypeId = $task->confirmTypeId;
                    $data['workContent'] = ConfirmType::findOne($confirmTypeId)->name;
                }else{
                    $workTypeId = $task->workTypeId;
                    $data['workContent'] = WorkType::findOne($workTypeId)->name;
                }
                if($audio){
                    $data['audio'] = $audio->fileName;
                }
                if(is_array($images)){
                    foreach($images as $image){
                        $data['images'][] = $image->fileName;
                    }
                }
            }
            return $data;
        }else{
            //No data invalid
            return false;
        }


    }

    /**
     * @param $id
     * @return bool
     */
    public function actionUpdateStatus($id){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post('data','');
            $work = Work::findOne($id);
            $response = false;
            //update comment confirm
            if(isset($data['officeComment']) && $data['officeComment']){
                $work->officeComment = $data['officeComment'];
            }
            $status = (isset($data['isConfirm']) && $data['isConfirm'] === "1") ? Constant::CONFIRM_WORK : Constant::DECLINE_WORK;
            //update status
            $work->status = $status;
            if($work->save()){
                $response = true;
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $response;
        }
    }

    private function queryWorkListData($organizationId,$key,$userCode,$isFilterSearch = false){
        $workedOrganizationId = (new Query())->select(['facilityId'])->from('organizationnamelevel')->where([$key => $organizationId]);
        $view = (new Query())->from('work')->where(['workedOrganizationId' => $workedOrganizationId]);
      //  $subtable = (new Query())->from(['w1' =>'work'])->where('w1.id = (SELECT id FROM work w2 WHERE w2.userCode = w1.userCode ORDER BY w2.updatedAt DESC LIMIT 1)');
        $level = Organization::findOne($organizationId)->organizationLevelId;
        if($level == Constant::FIFTH_LEVEL_ID){
            $user = (new Query())->from('user')->where(['userCode' => $userCode]);
            $query = (new Query()) ->select(['organizationnamelevel.facilityName',
                'organizationnamelevel.facilityId',
                'organizationnamelevel.fscName',
                'organizationnamelevel.officeName',
                'organizationnamelevel.branchName',
                'office_check_task.id as OfficeId',
                'self_check_task.id as SelfId',
                'confirm_type.name as officeContent',
                'work_type.name as selfContent',
                'work.id as workId',
                'user.userName',
                'user.userCode',
                'office_check_task.confirmTypeId',
                'self_check_task.workTypeId',
                'work.workedOrganizationId',
                'start',
                'end',
                'faOperationKind',
                'status',
                'officeComment',
                'date',
                'audio.fileName as audioName',
                'image.fileName as imageName',
                'confirm_type.aerialWork as officeArialWork',
                'work_type.aerialWork as selfArialWork',
                'work.checkType',
                'work.powerAIImage'])
                ->from(['work' => $view])
                ->innerJoin(['user' => $user],'user.userCode = work.userCode')
                ->leftJoin('office_check_task','office_check_task.workId = work.id')
                ->leftJoin('self_check_task','self_check_task.workId = work.id')
                ->leftJoin('audio','(audio.taskId = office_check_task.id and audio.checkType=work.checkType) 
                        or (audio.taskId = self_check_task.id and audio.checkType = work.checkType)')
                ->leftJoin('image','(image.taskId = office_check_task.id and image.checkType=work.checkType) 
                        or (image.taskId = self_check_task.id and image.checkType = work.checkType)')
                ->leftJoin('confirm_type','confirm_type.id = office_check_task.confirmTypeId')
                ->leftJoin('work_type','work_type.id = self_check_task.workTypeId')
                ->innerJoin('organizationnamelevel','organizationnamelevel.facilityId=work.workedOrganizationId');
        }else{
            $query = (new Query()) ->select(['organizationnamelevel.facilityName',
                'organizationnamelevel.facilityId',
                'organizationnamelevel.fscName',
                'organizationnamelevel.officeName',
                'organizationnamelevel.branchName',
                'office_check_task.id as OfficeId',
                'self_check_task.id as SelfId',
                'confirm_type.name as officeContent',
                'work_type.name as selfContent',
                'work.id as workId','user.userName',
                'office_check_task.confirmTypeId',
                'self_check_task.workTypeId',
                'work.workedOrganizationId',
                'start',
                'end',
                'faOperationKind',
                'status',
                'officeComment',
                'date',
                'audio.fileName as audioName',
                'image.fileName as imageName',
                'confirm_type.aerialWork as officeArialWork',
                'work_type.aerialWork as selfArialWork',
                'work.checkType',
                'work.powerAIImage'])
                ->from(['work' => $view])
                ->innerJoin('user','user.userCode = work.userCode')
                ->leftJoin('office_check_task','office_check_task.workId = work.id')
                ->leftJoin('self_check_task','self_check_task.workId = work.id')
                ->leftJoin('audio','(audio.taskId = office_check_task.id and audio.checkType=work.checkType) 
                        or (audio.taskId = self_check_task.id and audio.checkType = work.checkType)')
                ->leftJoin('image','(image.taskId = office_check_task.id and image.checkType=work.checkType) 
                        or (image.taskId = self_check_task.id and image.checkType = work.checkType)')
                ->leftJoin('confirm_type','confirm_type.id = office_check_task.confirmTypeId')
                ->leftJoin('work_type','work_type.id = self_check_task.workTypeId')
                ->innerJoin('organizationnamelevel','organizationnamelevel.facilityId=work.workedOrganizationId');
        }
        if(!$isFilterSearch){
            $query->orderBy('work.date desc');
        }

        return $query;
    }

    private function getWhereKey($organizationId){
        $organization = Organization::findOne($organizationId);
        $level = $organization->organizationLevelId;
        $key = '';
        switch ($level){
            case $level == 1:
                $key = 'headquartesrID';
                break;
            case $level == 2:
                $key = 'branchId';
                break;
            case $level == 3:
                $key = 'officeId';
                break;
            case $level == 4:
                $key = 'fscId';
                break;
            case $level == 5:
                $key = 'facilityId';
                break;
        }
        return $key;
    }

    public function actionWorkList(){
        $model = new FilterForm();
        $organizationId = Yii::$app->user->identity->organizationId;
        $key = $this->getWhereKey($organizationId);
        $data = $this->handleQueryWorkList($organizationId,$key);
        $result = $this->formatData($data);
        $provider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $company = $this->getCompanyInformation($organizationId,$key);
        //Clear button
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('data-work-list',
                [
                    'data' => $provider->getModels(),
                    'pages' => $provider->getPagination()
                ]
            );
        }

        return $this->render('work-list',
                    [
                        'data' => $provider->getModels(),
                        'pages' => $provider->getPagination(),
                        'company' => $company,
                        'workContent' => $this->getWorkContent(),
                        'model' => $model,
                        'result' => $result
                    ]
                );

    }

    private function getCompanyInformation($organizationId,$key){
        $data = (new Query())->from('organizationnamelevel')->where([$key => $organizationId])->createCommand()->queryAll();
        $result = [];
        foreach($data as $row){
            $result['facilityName'][] = $row['facilityName'];
            $result['fscName'][] = $row['fscName'];
            $result['officeName'][] = $row['officeName'];
            $result['branchName'][] = $row['branchName'];
        }
        $result['facilityName'] = array_unique($result['facilityName']);
        $result['fscName'] = array_unique($result['fscName']);
        $result['officeName'] = array_unique($result['officeName']);
        $result['branchName'] = array_unique($result['branchName']);
        return $result;
    }

    private function handleQueryWorkList($organizationId,$key){
        $data = $this->queryWorkListData($organizationId,$key,Yii::$app->user->identity->userCode)->createCommand()->queryAll();
        return $data;
    }

    private function getWorkContent(){
        $workType = WorkType::find()->select(['name'])->asArray()->all();
        $confirmType = ConfirmType::find()->select(['name'])->asArray()->all();
        $result = array_merge($workType,$confirmType);
        return $result;
    }

    private function formatData($data){
        $result = [];
        foreach ($data as $row){
            if(array_key_exists($row['workId'],$result)){
                $result[$row['workId']]['imageName'][] = $row['imageName'];
            }else{
                if($row['imageName']){
                    $row['imageName'] = [$row['imageName']];
                }
                $result[$row['workId']] = $row;
            }
        }
        //assign images
        foreach ($result as $key=>$row){
            $isOfficeTask = $row['OfficeId'] ? true : false;
            $result[$key]['imageName'] = $this->assignImage($row['imageName'],$isOfficeTask,$row['powerAIImage']);
        }
        return $result;
    }

    private function assignImage($images,$isOfficeTask,$powerAIImage){
        $imageName = [];

        $imageName['picture1'] = '';
        $imageName['picture2'] = '';
        $imageName['picture3'] = '';
        if(!$isOfficeTask && $powerAIImage){
            $image = substr($powerAIImage,0,-4);
            $imageName['picture1'] = $image.Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
        }
        if(is_array($images)){
           // $isOfficeTask = $subRow['OfficeId'] ? true : false;
            if($isOfficeTask){
                $imageName = $this->officeTaskImages($images,$imageName);
            }else{
               $imageName = $this->selfTaskImages($images,$imageName);
            }
        }
        return $imageName;
    }

    private function officeTaskImages($images,$imageName){
        $lengthPrefixImage = strripos($images[0],'_');
        $prefixImage = substr($images[0],0,($lengthPrefixImage+1));
        //Picture 1
        if(array_search($prefixImage.'1'.Constant::IMAGE_EXTENSION,$images) !== false){
            //echo 'Office - 1 --'.$prefixImage.'1'.Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
            $imageName['picture1'] = $prefixImage.'1'.Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
        }
        //Picture2
        $findImage2 = '';
        foreach ($images as $image){
            if($image !== $prefixImage.'1'.Constant::IMAGE_EXTENSION){
                $findImage2 = substr($image,0,-4).Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
                break;
            }
        }
        if($findImage2){
            // echo 'Office > 2'.$findImage2;
            $imageName['picture2'] = $findImage2;
        }
        //Picture3
        $findImage3 = '';
        foreach ($images as $image){
            if($image !== $prefixImage.'1'.Constant::IMAGE_EXTENSION && $image !== $prefixImage.'2'.Constant::IMAGE_EXTENSION ){
                $findImage3 = substr($image,0,-4).Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
                //$findImage3 = $image;
                break;
            }
        }
        if($findImage3){
            // echo 'Office > 3 --'.$findImage;
            $imageName['picture3'] = $findImage3;
        }
        return $imageName;
    }

    private function selfTaskImages($images,$imageName){
        $lengthPrefixImage = strripos($images[0],'_');
        $prefixImage = substr($images[0],0,($lengthPrefixImage+1));
        //Picture 2
        if(array_search($prefixImage.'1'.Constant::IMAGE_EXTENSION,$images)){
            //  echo 'Self - 1--'.$prefixImage.'1thumbnail'.Constant::IMAGE_EXTENSION;
            $imageName['picture2'] = $prefixImage.'1'.Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
        }
        //Picture 3
        if(array_search($prefixImage.'2'.Constant::IMAGE_EXTENSION,$images)){
            //echo $prefixImage.'2thumbnail'.Constant::IMAGE_EXTENSION;
            $imageName['picture3'] = $prefixImage.'2'.Constant::IMAGE_THUMBNAIL.Constant::IMAGE_EXTENSION;
        }
        return $imageName;
    }

    public function actionFilterSearch(){
        if(Yii::$app->request->isAjax){
            $organizationId = Yii::$app->user->identity->organizationId;
            $key = $this->getWhereKey($organizationId);
            $model = new FilterForm();
            if($model->load(Yii::$app->request->post(),'FilterForm')){
                $fields = $this->convertFilterToFields($model->attributes);
                $data = $this->handleQueryFilter($fields,$organizationId,$key)->createCommand()->queryAll();
                $result = $this->formatData($data);
                $provider = new ArrayDataProvider([
                    'allModels' => $result,
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                ]);
                return $this->renderAjax('data-work-list',
                    [
                        'data' => $provider->getModels(),
                        'pages' => $provider->getPagination()
                    ]
                );

            }
        }
        return false;
//
    }

    private function convertFilterToFields($attributes){
        $fields = FilterForm::convertPropertyFilter();
       // $notUsingFields = ['projectId','startFinishTime','endFinishTime','employee','kyDivision'];
        $convertFields = [];
        foreach ($attributes as $key=>$value){
            if(!isset($fields[$key])){
                continue;
            }
            $convertFields[$fields[$key]] = $value;
        }
        return $convertFields;
    }

    private function handleQueryFilter($fields,$organizationId,$key){
        $query = $this->queryWorkListData($organizationId,$key,Yii::$app->user->identity->userCode,false);
        $checkType = '';
        foreach ($fields as $key => $value){
            if($value && $key == Constant::START_DATE){
                $query->andWhere(['>=','date',$value]);
                continue;
            }
            if($value && $key == Constant::END_DATE){
                $query->andWhere(['<=','date',$value]);
                continue;
            }
            if($value){
                switch($key){
                    case Constant::CHECK_TYPE:
                        $checkType = $value;
                        $query->andWhere([$key => $value]);
                        break;
                    case Constant::AERIAL_WORK:
                        if($checkType){
                            $newKey = $checkType == Constant::OFFICE_TASK ? 'confirm_type.aerialWork' : 'work_type.aerialWork';
                            $query->andWhere([$newKey => $value]);
                        }else{
                            $query->andWhere('confirm_type.aerialWork=:value or work_type.aerialWork=:value',[':value' => $value]);
                        }
                        break;
                    case Constant::WORKING_EMPLOYEE:
                        $query->andWhere('user.userName=:value or user.userCode=:value',[':value' => $value]);
                        break;
                    case Constant::WORK_CONTENT:
                        if($checkType){
                            $newKey = $checkType == Constant::OFFICE_TASK ? 'confirm_type.name' : 'work_type.name';
                            $query->andWhere([$newKey => $value]);
                        }else{
                            $query->andWhere('confirm_type.name=:value or work_type.name=:value',[':value' => $value]);
                        }
                        break;
                    case Constant::RESULT:
                        if($value === Constant::CONFIRMED){
                            $query->andWhere([ $key => Constant::CONFIRM_WORK]);
                        }
                        else if($value === Constant::COMMENT){
                            $query->andWhere('officeComment is not null');
                        }
                        else if($value === Constant::NOT_FINISH){
                          //  $query->andWhere('status = '.Constant::IS_WORKING_2.' or status = '.Constant::IS_WORKING_4);
                            $query->andWhere('status=:working_2 or status=:working_4',
                                    [':working_2' => Constant::IS_WORKING_2,':working_4' =>  Constant::IS_WORKING_4]);
                        }else{
                            $query->andWhere('status is null');
                        }
                        break;
                    default:
                        $query->andWhere([$key => $value]);


                }
            }

        }
        $query->orderBy('work.date desc');
        return $query;
    }

    private function queryMediaDownload($workId){
        $workArray = (new Query())->from('work')->where(['id' => $workId]);
        $data = (new Query()) ->select([
            'office_check_task.id as OfficeId',
            'self_check_task.id as SelfId',
            'work.id as workId',
            'audio.fileName as audioName',
            'image.fileName as imageName',
            'work.powerAIImage'])
            ->from(['work' => $workArray])
            ->leftJoin('office_check_task','office_check_task.workId = work.id')
            ->leftJoin('self_check_task','self_check_task.workId = work.id')
            ->leftJoin('audio','(audio.taskId = office_check_task.id and audio.checkType=work.checkType) 
                    or (audio.taskId = self_check_task.id and audio.checkType = work.checkType)')
            ->leftJoin('image','(image.taskId = office_check_task.id and image.checkType=work.checkType) 
                    or (image.taskId = self_check_task.id and image.checkType = work.checkType)')->createCommand()->queryAll();

        return $data;
    }

    public function actionDownloadWorksMedia(){
        if(Yii::$app->request->isPost){
            $workIds = Yii::$app->request->post();
//            $workArray =  explode(",",$workIds);
//            $queryData = $this->queryMediaDownload($workArray);
//            $result = $this->formatData($queryData);
//            $path = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR. 'pwd.txt';
//            $zipPath = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR."download.zip";
//            $zip = new \ZipArchive();
//            if($zip->open($zipPath,ZipArchive::CREATE) === true){
//                $zip->addFile($path,'pwd.txt');
//                $zip->close();
//                return Yii::$app->response->sendFile($zipPath,'画像_音声.zip');
//            }
            return Yii::$app->request->post();
        }

    }

    private function downloadMedia($data){
        $zipPath = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR. 'test.zip';
        $uploadPath = Yii::$app->params['uploadFolder'].DIRECTORY_SEPARATOR;
        $zip = new \ZipArchive();
        if($zip->open($zipPath,ZipArchive::CREATE) === true){
            foreach ($data as $row){
                if($row['audioName']){
                    $zip->addFile($uploadPath.$row['audioName'],$row['audioName']);
                }
                if(is_array($row['imageName'])){
                    foreach ($row['imageName'] as $image){
                        $zip->addFile($uploadPath.$image,$image);
                    }
                }
            }
            $zip->close();
            return Yii::$app->response->sendFile($zipPath,'画像_音声.zip');
        }
    }

    public function download(){
        $path = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR. 'pwd.txt';
        $zipPath = Yii::getAlias("@runtime").DIRECTORY_SEPARATOR."download.zip";
        $zip = new \ZipArchive();
        if($zip->open($zipPath,ZipArchive::CREATE) === true){
            $zip->addFile($path,'pwd.txt');
            $zip->close();
            return Yii::$app->response->sendFile($zipPath,'画像_音声.zip');
        }

    }
}