<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$show = Yii::t('app', 'Show')
?>
<div class="site-login">
    <h1 id="form-title"><?= Yii::t('app','Login')?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
    ]); ?>

        <label for="userCode"><?= Yii::t('app','User code')?></label>
        <?= $form->field($model, 'userCode',['template' => "<div style='display: flex;'><i class=\"fa fa-user icon\"></i>{input}</div>\n<div>{error}</div>"])->textInput(['id'=>'userCode']) ?>

        <label for="password"><?= Yii::t('app','Password')?></label>
        <?= $form->field($model, 'password',['template' => "<div style='display: flex;'><i class=\"fa fa-lock icon\"></i>{input}<span id='toggle-password'>{$show}</span></div>\n<div>{error}</div>"])->passwordInput(['id' => 'password']) ?>

        <div id="button-group">
            <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button','id' => 'btn-login']) ?>
        </div>
        <p id="note-message"><?= Yii::t('app','First login or forget your password please choose reset pasword button')?></p>
        <?php
        if($model->hasErrors('isBlocked')){
        ?>
            <p id="block-account"><?= Yii::t('app','Your account is blocked') ?></p>

        <?php }?>
        <div id="reset-password-block">
            <?= Html::a(Yii::t('app', 'Reset password'),['user/reset-password'],['class' => 'btn btn-default','id'=>'btn-reset-password'])?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerCssFile('@web/css/login.css')?>
<?php $this->registerJsFile('@web/js/login.js',['depends' => 'yii\web\JqueryAsset'])?>
<script>
    var hide = "<?= Yii::t('app','Hide')?>";
    var show = "<?= Yii::t('app','Show')?>";
</script>