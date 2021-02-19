<?php

use app\models\User;use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


?>
<?php
    $dropdownItems = [
        ['label' => Yii::t('app','User'), 'url' => '#'],
        ['label' => Yii::t('app','Recommend'), 'url' => '#'],
        ['label' => Yii::t('app','Facility'), 'url' => '#'],
        ['label' => Yii::t('app','Upload FA'), 'url' => '#'],
    ];
    $items = [
        [
            'label' => Yii::t('app','Top'),
            'url' => !User::isFacilityCompany(Yii::$app->user->identity->organizationId) ? Url::to(['dash-board/index']) : Url::to(['dash-board/facility-company','organizationId' => Yii::$app->user->identity->organizationId]),
            'options' => [
                'class' => 'ul-group-1'
            ]
        ],
        [
            'label' => Yii::t('app','Work List'),
            'options' => [
                'class' => 'ul-group-1'
            ],
            'url' => Url::to(['work/work-list'])
        ],
        [
            'label' => Yii::t('app','Manage'),
            'items' => $dropdownItems,
            'options' => [
                'id' => 'dropdown-group',
                'class' => 'ul-group-1'
            ],

        ],
    ];

    if(!Yii::$app->user->isGuest){
        $items[] = [
            'label' => Yii::$app->user->identity->userName,
            'options' => [
                'class' => 'ul-group-2'
                ]
            ];
        $items[] = [
            'label' => Yii::t('app','Logout'),
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'POST'],
            'options' => [
                'class' => 'ul-group-2',
                'id' => 'logout'
            ]
        ];
    }
?>
<?php
    $url = !User::isFacilityCompany(Yii::$app->user->identity->organizationId) ? Url::to(['dash-board/index']) : Url::to(['dash-board/facility-company','organizationId' => Yii::$app->user->identity->organizationId]);

    NavBar::begin([
        'brandLabel' => Yii::t('app','Homepage1'),
        'brandUrl' => $url,
        'options' => [
            'class' => 'navbar navbar-default',
            'id' => 'header-common'
        ],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);

    echo  Nav::widget([
        'options' => ['class' => 'navbar-nav nav-center'],
        'items' => $items,
    ]);
    NavBar::end();
?>



