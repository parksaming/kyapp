<?php


namespace app\controllers;


use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class PageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'controllers' => ['dash-board'],
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !User::isFacilityCompany(Yii::$app->user->identity->organizationId);
                        }
                    ],
                    [
                        'controllers' => ['dash-board'],
                        'actions' => ['facility-company'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'controllers' => ['work'],
                        'actions' => ['work-detail'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'controllers' => ['recommend'],
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::canManageRecommend(Yii::$app->user->identity->roleId,Yii::$app->user->identity->organizationId);
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]

                ],
            ],
        ];
    }
}