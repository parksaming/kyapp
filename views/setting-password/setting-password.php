<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/** @var string $userCode */
$show = Yii::t('app', 'Show');
$passwordLabel = Yii::t('app','New password');
$repasswordLabel = Yii::t('app','Re-password');
?>
<div>
    <h3><?= Yii::t('app','Setting Password') ?></h3>
    <div id="reset-password-content">
        <div class="alert alert-info">
            <p style="font-size: 24px;font-weight: 700;"><?= Yii::t('app','Note setting password')?></p>
            <ul>
                <li><?= Yii::t('app','Password length: 8-16')?></li>
                <li><?= Yii::t('app','Password includes more than 2 types such as alphabet and number,etc,..')?></li>
                <li><?= Yii::t('app','Do not use password includes the same characters such as 0000,1234,abcd')?></li>
                <li><?= Yii::t('app','Do not allow password including user code')?></li>
            </ul>
        </div>

        <?php $form = ActiveForm::begin(
                [
                     'id' => 'setting-password-form',
                ]
        )?>

            <div class="form-group" style="margin-bottom:5px;">
                <label for="userCode" class='setting-pwd-label'><?= Yii::t('app','User code')?></label>
                <span style="margin-left:-32px;font-weight: 700"><?= $userCode ?></span>
            </div>

            <?= $form->field($model, 'password',['template' => "<div style='display: flex;'><label for=\"new-password\" class='setting-pwd-label'>$passwordLabel</label>{input}<span id='toggle-password' class='password-mask'>{$show}</span></div>\n<div class='custom-error'>{error}</div>"])->passwordInput(['id'=>'setting-pwd']) ?>

            <?= $form->field($model, 'repassword',['template' => "<div style='display: flex;'><label for=\"re-password\"  class='setting-pwd-label'>$repasswordLabel</label>{input}<span id='toggle-password' class='password-mask'>{$show}</span></div>\n<div class='custom-error'>{error}</div>"])->passwordInput(['id'=>'setting-re-pwd']) ?>

            <?= $form->field($model, 'userCode')->hiddenInput(['value' => $userCode])->label(false) ?>

            <?php if($model->hasErrors('server-error')){?>
                <p class="custom-error"><?= $model->getFirstError('server-error')?></p>

            <?php }?>

            <div id="button-group">
                <?= Html::submitButton(Yii::t('app','Register'),['class' => 'btn btn-primary','id' => 'btn-register-pwd'])?>
            </div>

        <?php ActiveForm::end()?>
    </div>
    <!--Confirm send mail modal-->
    <?php
    Modal::begin([
        'header' =>  Yii::t('app','Confirm change password'),
        'id' => 'confirm-change-pwd-modal',
        'closeButton' => false,
    ]);
    ?>
    <div class="modal-body">
        <div id="confirm-message">
            <p><?= Yii::t('app','Password will change. Please confirm it?')?></p>
        </div>
        <div id="btn-group">
            <button class="btn btn-default" id='btn-change-cancel'> <?= Yii::t('app','Cancel')?></button>
            <button class="btn" id='btn-change-pwd'><?= Yii::t('app','Yes')?></button>
        </div>
    </div>
    <?php Modal::end(); ?>

    <!--Confirm send mail modal-->
    <?php
    Modal::begin([
        'header' =>  Yii::t('app','Change password notice'),
        'id' => 'change-pwd-notice',
        'closeButton' => false,
    ]);
    ?>
    <div class="modal-body">
        <div id="confirm-message">
            <p><?= Yii::t('app','Changing password completed. You can login now')?></p>
        </div>
        <div id="btn-group">
            <button class="btn btn-success" id='btn-login'><?= Yii::t('app','Login')?></button>
        </div>
    </div>
    <?php Modal::end(); ?>

</div>
<script>
    var validateSettingForm = "<?= Yii::$app->session->hasFlash('success') ? 1 : 0 ?>";
    var urlChangePassword = "<?= Url::to(['setting-password/change-password','userCode' => $userCode])?>";
    var urlLogin = "<?= Url::to(['site/login'])?>";
    var hide = "<?= Yii::t('app','Hide')?>";
    var show = "<?= Yii::t('app','Show')?>";
    var csrf = "<?= Yii::$app->request->csrfToken; ?>";
</script>
<?php $this->registerCssFile('@web/css/login.css')?>
<?php $this->registerJsFile('@web/js/setting-password.js',['depends' => 'yii\web\JqueryAsset'])?>