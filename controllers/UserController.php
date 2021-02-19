<?php


namespace app\controllers;

use app\Constant\Constant;
use Codeception\PHPUnit\Constraint\Page;
use Yii;
use app\models\Organization;
use app\models\ResetPasswordForm;
use app\models\User;
use app\models\Work;
use yii\helpers\Json;
use yii\web\Controller;

class UserController extends Controller
{
    public $layout = 'main-login';

    /**
     * @return array|string
     */
    public function actionResetPassword(){
        $model = new ResetPasswordForm;
        if($model->load(Yii::$app->request->post()) &&  $model->validate()){
            if($model->validateUser()){
                Yii::$app->session->setFlash('success','Success');
            }
        }
        return $this->render('reset-password',['model' =>$model]);
    }


    public function actionTest(){
        $pwd = 'AAAAFT1000020?$';
        $password = str_split($pwd);
        $isNumber = $isLowerCase = $isUpperCase = $isSpecialCharacter = $hasUserCode = false;

        $userCode = 'FT1000020';

        if(stripos($pwd,$userCode)){
            $hasUserCode = true;
        }
        if(!$hasUserCode){
            $check = 0;
            foreach ($password as $character){
                if(ctype_digit($character)){
                    $isNumber = true;
                }
                else if(ctype_upper($character)){
                    $isUpperCase = true;
                }
                else if(ctype_lower($character)){
                    $isLowerCase = true;
                }
                else{
                    $isSpecialCharacter = true;
                }
                $check = $isNumber + $isUpperCase + $isLowerCase + $isSpecialCharacter;
                if($check >= 2){
                    break;
                }

            }
            if($check >= 2){
                echo 'Valid Password';
            }else{
               echo 'Invalid password';
            }
        }else{
            echo 'Password doesn\'t include userCode';
        }


    }

    public function actionTest1(){
        $userCode = 'AC1000001';
        $user = User::findByUserCode($userCode);
        if($user){
            $organizationId = $user->organizationId;
            $organization = Organization::findOne($organizationId);
            $data = $this->getData($organization);
            return $this->render('index',['data' =>  $data]);
        }else{
            echo 'No data';
        }
    }



    /**
     * @param $organization Organization
     * @return array
     */
    private function getData($organization){
        $data = [];
        $data['name'] = $organization->organizationName;
        $data['level'] = $organization->organizationLevelId;
        $data['id'] = $organization->id;
        if ($organization->organizationLevelId == 5){
            $data['numberOfUser'] = $organization->numberOfUsers;
            $data['peopleAreWorking'] = intval($this->totalPeopleWorking($data['id']));
            $data['peopleAreWaiting'] = intval($this->totalPeopleWaitConfirm($data['id']));
            return $data;
        }
        $dataChild = Organization::findAll(['parentId' => $organization->id]);

        foreach($dataChild as $item){
            $data['child'][] = $this->getData($item);
        }
        $totalUser = $totalPeopleAreWorking = $totalPeopleAreWaiting = 0;
        if(isset($data['child'])){
            foreach ($data['child'] as $item){
                if(isset($item['numberOfUser'])){
                    $totalUser += $item['numberOfUser'];
                    $totalPeopleAreWorking += $item['peopleAreWorking'];
                    $totalPeopleAreWaiting += $item['peopleAreWaiting'];
                }
                if(isset($item['totalUser'])){
                    $totalUser += $item['totalUser'];
                    $totalPeopleAreWorking += $item['totalPeopleAreWorking'];
                    $totalPeopleAreWaiting += $item['totalPeopleAreWaiting'];
                }
            }

        }
        $data['totalUser'] = $totalUser;
        $data['totalPeopleAreWorking'] = $totalPeopleAreWorking;
        $data['totalPeopleAreWaiting'] = $totalPeopleAreWaiting;
        return $data;
    }

    public function actionTest2(){
        $userCode = 'AC1000001';
        $user = User::findByUserCode($userCode);
        if($user){
            $organizationId = $user->organizationId;
            $organization = Organization::findOne($organizationId);
            $data = $this->getData($organization);

        }

        return $this->render('index2',['data' =>  $data]);
    }

    private function totalPeopleWorking($id){
        $sql = 'select count(id) as peopleAreWorking from work 
                where updatedAt IN (SELECT max(updatedAt) as latestTime FROM work WHERE workedOrganizationId=:id
                group by userCode)
                and (status=:status2 or status=:status4)';
//        $sql = 'SELECT count(DISTINCT userCode) as peopleAreWorking from work
//                WHERE workedOrganizationId=:id  and (status=:status2 or status=:status4)';
        $params = [
            ':id' => $id,
            ':status2' =>  Constant::IS_WORKING_2,
            ':status4' => Constant::IS_WORKING_4
        ];
        $total = Work::findBySql($sql,$params)->all();
        return $total[0]->peopleAreWorking;
    }

    private function totalPeopleWaitConfirm($id){
        $sql = 'select count(id) as peopleAreWaiting from work 
                where updatedAt IN (SELECT max(updatedAt) as latestTime FROM work WHERE workedOrganizationId=:id
                group by userCode)
                and status=:statusWaiting and date(updatedAt) = CURRENT_DATE';
//        $sql = 'SELECT count(DISTINCT userCode) as peopleAreWaiting from work
//                WHERE workedOrganizationId=:id  and status=:statusWaiting and date = CURRENT_DATE';
        $params = [
            ':id' => $id,
            ':statusWaiting' =>  Constant::IS_WAITING_CONFIRM,
        ];
        $total = Work::findBySql($sql,$params)->all();
        return $total[0]->peopleAreWaiting;
    }


}