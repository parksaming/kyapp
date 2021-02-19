<?php


namespace app\controllers;


use app\models\SettingPasswordForm;
use app\models\User;
use Yii;
use yii\base\Security;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

class SettingPasswordController extends Controller
{
    public $layout = 'main-login';
    //setting password

    /**
     * @param $token
     * @return string
     */
    public function actionIndex($token){
        /** @var User $user */
        $user = User::findOne(['token' => $token]);
        if($user && (strtotime($user->tokenCreateAt)+3600) >= strtotime('now')) {
            $model = new SettingPasswordForm();
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                Yii::$app->session->setFlash('success','Validate Success');
            }
            return $this->render('setting-password',['model' => $model,'userCode' => $user->userCode]);
        }
        return $this->render('expired-registration');

    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function actionSendEmail(){
        if(Yii::$app->request->isAjax ){
            $userCode = Yii::$app->request->get('userCode','');
            $user = User::findByUserCode($userCode);
            if($user){
                if($user->token == '' || (strtotime($user->tokenCreateAt) + 3600) < strtotime('now') ){
                    $user->token = Yii::$app->security->generateRandomString() . '_' . time();
                    $user->tokenCreateAt = date('Y-m-d H:i:s');
                    if(!$user->save()){
                        return false;
                    }
                }
                return Yii::$app->mailer->compose('template-mail',['user' => $user])
                    ->setFrom('admin@example.com')
                    ->setTo($user->email)
                    ->setSubject(Yii::t('app','Email Subject'))
                    ->send();
            }
        }


    }

    /**
     * @param $userCode
     * @return bool|string
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionChangePassword($userCode){
        Yii::$app->response->format = 'json';
        if(Yii::$app->request->isAjax){
            $user = User::findByUserCode($userCode);
            if($user){
                $canSave = (strtotime($user->tokenCreateAt)+3600) >= strtotime('now');
                if(!$canSave){
                    return $this->render('expired-registration');
                }
                $user->password = Yii::$app->request->post('password','');
                if($user->validate('password')){
                    $user->isLocked = 0;
                    $user->attemptCount = 0;
                    $user->token = '';
                    $user->tokenCreateAt  = 0;
                    $user->password = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('password'));
                    if($user->update()){
                        return true;

                    }
                }
            }
        }
        return false;
    }

    public function actionExpiredRegistration(){
        return $this->render('expired-registration');
    }
}