<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Reset Password';
?>

<h3 id="reset-password-title"><?= Yii::t('app','Reset Pasword page')?></h3>
<a id = 'return' href="<?= Url::to(['site/login'])?>"><i class="fa fa-arrow-left" aria-hidden="true"></i><?= Yii::t('app','Return')?></a>

<div id="reset-password-content">
    <h4 id="reset-note"><?= Yii::t('app','Please enter user code and choose verify email')?></h4>
    <?php $form = ActiveForm::begin(
            [
                'id' => 'reset-password-form',
            ]
    )?>
        <label for="userCode"><?= Yii::t('app','User code')?></label>
        <?= $form->field($model, 'userCode',['template' => "<div style='display: flex;'><i class=\"fa fa-user icon\"></i>{input}</div>\n<div>{error}</div>"])->textInput(['id'=>'reset-userCode']) ?>
       <div id="button-group">
           <?= Html::submitButton(Yii::t('app','Verify email'),['class' => 'btn btn-primary','id' => 'btn-verify-email'])?>
       </div>
    <?php ActiveForm::end()?>
</div>

<!--Confirm send mail modal-->
<?php
Modal::begin([
    'header' =>  Yii::t('app','Confirm send email'),
    'id' => 'send-mail-modal',
    'closeButton' => false,
]);
?>
<div class="modal-body">
        <div id="confirm-message">
            <p><?= Yii::t('app','Would you like sending mail with this user code?')?></p>
        </div>
        <div id="btn-group">
            <button class="btn btn-default" id='btn-cancel'> <?= Yii::t('app','Cancel')?></button>
            <button class="btn" id='btn-send'><?= Yii::t('app','Send')?></button>
        </div>
    </div>
<?php Modal::end(); ?>

<!--Alert check mail modal-->
<?php
Modal::begin([
    'header' =>  Yii::t('app','Send Email Notice'),
    'id' => 'check-mail-modal',
    'closeButton' => false,
    'size' => 'modal-lg'
]);
?>
<div class="modal-body">
    <div id="confirm-message">
        <p><?= Yii::t('app','Please check your email to reset password')?></p>
    </div>
    <div id="btn-group">
        <button class="btn btn-success" id='btn-close-mail'><?= Yii::t('app','Close')?></button>
    </div>
</div>
<?php Modal::end(); ?>

<?php $this->registerCssFile('@web/css/login.css')?>
<?php $this->registerJsFile('@web/js/reset-password.js',['depends' => 'yii\web\JqueryAsset'])?>
<script>
    var urlSendMail = "<?= Url::to(['setting-password/send-email'])?>";
    var isValidateSuccess = <?= Yii::$app->session->hasFlash('success') ? 1 : 0 ?>;
</script>
