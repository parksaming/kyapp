<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div id="expired-registration-content">
    <p><?= Yii::t('app','Registration link has expired')?></p>
    <p><?= Yii::t('app','Time out is one hour')?></p>
    <p><?= Yii::t('app','Please visit login page to setting new password')?></p>
    <div id="btn-group" style="margin:80px auto">
        <a href="<?= Url::to(['site/login'])?>" class="btn btn-info" style="width: 160px;"><?= Yii::t('app','Login')?></a>
    </div>

</div>

<?php $this->registerCssFile('@web/css/login.css')?>
