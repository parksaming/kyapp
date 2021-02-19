<?php


namespace app\controllers;

use yii;
use app\Constant\Constant;
use app\models\Organization;
use app\models\Work;
use app\models\User;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class DashBoardController extends PageController
{
    //dashboard level 1,2,3
    public function actionIndex(){
        $organizationId = Yii::$app->user->identity->organizationId;
        $organization = Organization::findOne($organizationId);
        $dataQuantity = $this->formatDataQuantity();
        $data = $this->getData($organization,$dataQuantity);
        return $this->render('index',['data' => $data]);
    }
    //passing levelId
    public function actionFacilityCompany($organizationId){
        $key = $this->getWhereKey($organizationId);
        //get numberOfUser,peopleAreWaiting,peopleAreWorking
        $information = $this->getInformationOrganization($organizationId,$key);
        $query = $this->queryDataForUser($organizationId,$key);
        $isChecked = false;
        if(Yii::$app->request->isAjax){
            $checkBoxes = Yii::$app->request->get();
            if($checkBoxes['cb-working'] == "1"){
                $query->orWhere('status = 2 or status = 4');
                $isChecked = true;
            }
            if($checkBoxes['cb-confirm'] == "1"){
                $query->orWhere('status = 3');
                $isChecked = true;
            }
//                if($checkBoxes['cb-decline']){
//
//                }
            if($checkBoxes['cb-finished'] == "1"){
                $query->orWhere('status = 6');
                $isChecked = true;
            }
        }
        //uncheck all checkbox or first access page
        if(!$isChecked){
            $query->orWhere('status = 2 or status = 4')->orWhere('status = 3');
        }
        $query->orderBy([new yii\db\Expression('field (status,3) DESC')])
            ->addOrderBy('work.updatedAt asc,start DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $this->render('facility-company',['dataProvider' => $dataProvider,'information' => $information,'id' => $organizationId]);
    }

    private function getData($organization,$dataQuantity){
        $data = [];
        $data['name'] = $organization->organizationName;
        $data['level'] = $organization->organizationLevelId;
        $data['id'] = $organization->id;
        if ($organization->organizationLevelId == 5){
            $data['numberOfUser'] = $organization->numberOfUsers;
            $data['peopleAreWorking'] = isset($dataQuantity[$organization->id]) ? $dataQuantity[$organization->id]['peopleAreWorking'] : '';
            $data['peopleAreWaiting'] = isset($dataQuantity[$organization->id]) ? $dataQuantity[$organization->id]['peopleAreWaiting'] : '';
            return $data;
        }
        $dataChild = Organization::findAll(['parentId' => $organization->id]);

        foreach($dataChild as $item){
            $data['child'][] = $this->getData($item,$dataQuantity);
        }
        $totalUser = $totalPeopleAreWorking = $totalPeopleAreWaiting = 0;
        if(isset($data['child'])){
            foreach ($data['child'] as $item){
                $totalUser += $item['numberOfUser'];
                $totalPeopleAreWorking += is_numeric($item['peopleAreWorking']);
                $totalPeopleAreWaiting += is_numeric($item['peopleAreWaiting']);
            }

        }
        $data['numberOfUser'] = $totalUser;
        $data['peopleAreWorking'] = $totalPeopleAreWorking;
        $data['peopleAreWaiting'] = $totalPeopleAreWaiting;
        return $data;
    }

    private function getDataQuantity(){
        $sql = 'SELECT workedOrganizationId, sum( case when status=:statusWaiting then 1 else 0 end) as peopleAreWaiting, 
                sum( case when  status=:status2  or status=:status4 then 1 else 0 end) as peopleAreWorking from work w1 
                WHERE w1.id = (SELECT id FROM work w2 WHERE w2.userCode = w1.userCode and w2.date = CURRENT_DATE ORDER BY w2.updatedAt DESC LIMIT 1) 
                GROUP BY w1.workedOrganizationId';
        $params = [
            ':status2' =>  Constant::IS_WORKING_2,
            ':statusWaiting' =>  Constant::IS_WAITING_CONFIRM,
            ':status4' => Constant::IS_WORKING_4
        ];
        $quantity = Work::findBySql($sql,$params)->all();
        return $quantity;
    }

    private function formatDataQuantity(){
        $dataQuantity = $this->getDataQuantity();
        $result = [];
        foreach($dataQuantity as $row){
            $result[$row->workedOrganizationId] = [
              'peopleAreWorking' => $row->peopleAreWorking,
              'peopleAreWaiting' => $row->peopleAreWaiting,
            ];
        }
        return $result;
    }

    private function queryDataForUser($organizationId,$key){
        $view = (new Query())->from('organizationnamelevel')->where([$key => $organizationId]);
        $subtable = (new Query())->from(['w1' =>'work'])->where('w1.id = (SELECT id FROM work w2 WHERE w2.userCode = w1.userCode and w2.date = \'2020-01-15\' ORDER BY w2.updatedAt DESC LIMIT 1)');
        $query = (new Query()) ->select(['organizationnamelevel.facilityName','organizationnamelevel.fscName','organizationnamelevel.officeName','organizationnamelevel.branchName',
                'office_check_task.id as OfficeId', 'self_check_task.id as SelfId', 'confirm_type.name as officeContent','work_type.name as selfContent','work.id','user.userName',
                'office_check_task.confirmTypeId','self_check_task.workTypeId','work.workedOrganizationId','organization.organizationName','start', 'end','faOperationKind','status'])
                ->from(['organizationnamelevel' => $view])
                ->innerJoin(['work' => $subtable],'work.workedOrganizationId = organizationnamelevel.facilityId')
                ->innerJoin('user','user.userCode = work.userCode')
                ->innerJoin('organization','organization.id = work.workedOrganizationId')
                ->leftJoin('office_check_task','office_check_task.workId = work.id')
                ->leftJoin('confirm_type','confirm_type.id = office_check_task.confirmTypeId')
                ->leftJoin('self_check_task','self_check_task.workId = work.id')
                ->leftJoin('work_type','work_type.id = self_check_task.workTypeId');
        return $query;
    }
    public function actionTest1(){
        $userCode = 'AC1000008';
        $organizationId = User::findByUserCode($userCode)->organizationId;
        $key = $this->getWhereKey($organizationId);
        $dataProvider = $this->queryDataForUser($organizationId,$key);
        var_dump($dataProvider);
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

    private function getInformationOrganization($organizationId,$key){
        $numberOfUser = Organization::findOne($organizationId)->numberOfUsers;
        $view = (new Query())->from('organizationnamelevel')->where([$key => $organizationId]);
        $subTable = (new Query())->select(['workedOrganizationId','sum( case when status=3 then 1 else 0 end) as peopleAreWaiting','sum( case when  status=2  or status=4 then 1 else 0 end) as peopleAreWorking'])
            ->from('work')->where('work.id = (SELECT id FROM work w2 WHERE w2.userCode = work.userCode and w2.date = CURRENT_DATE ORDER BY w2.updatedAt DESC LIMIT 1)')
            ->groupBy('work.workedOrganizationId');
        $query = (new Query())->select(['sum(table2.peopleAreWaiting) as waiting','sum(table2.peopleAreWorking) as working','table1.headquartesrID'])->from(['table1' => $view])
            ->innerJoin(['table2' => $subTable],'table1.facilityId = table2.workedOrganizationId');
        $data = $query->createCommand()->queryOne();
        $result = [
            'numberOfUser' => $numberOfUser,
            'peopleAreWaiting' => $data['waiting'],
            'peopleAreWorking' => $data['working'],
        ];
        return $result;
    }

    public function actionTest(){
        return 'hello';
    }


}